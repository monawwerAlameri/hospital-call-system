// ============================================================
//  HOSPITAL CALL SYSTEM — AUDIO ENGINE  v5.1
//  Uses REAL reference audio files for chime tones
//  Ref1 (chime-general.mp4) → General/employee calls
//  Ref2 (chime-code.mp4)    → Emergency code calls
//  Speech remains dynamic via Text-to-Speech
//  King Khalid Hospital, Hail
//  v5.1 changes:
//   - Improved audio quality (clearer pitch, natural rate)
//   - Shorter pauses between phrases (default 250ms)
//   - Removed duplicate "Attention please" repetition
//   - Enabled Arabic speech synthesis (was previously skipped)
//   - Better voice picking for ar-SA
// ============================================================

const Audio = (() => {
    let audioCtx = null;

    // Preloaded audio buffers
    let generalChimeBuffer = null;
    let codeChimeBuffer = null;
    // v5.1.1: Raw ArrayBuffers fetched on page load (no AudioContext needed)
    let generalChimeRaw = null;
    let codeChimeRaw = null;
    let buffersLoading = false;
    let buffersLoaded = false;

    // How many seconds of each file to play (ONLY the chime/tone, NO speech)
    const GENERAL_CHIME_DURATION = 2.5;  // First 2.5s of ref1 (tone only, no speech)
    const CODE_CHIME_DURATION = 2.8;     // First 2.8s of ref2 (tone only, no speech)

    // Base path for audio files
    const AUDIO_BASE = (() => {
        const scripts = document.querySelectorAll('script[src*="audio.js"]');
        if (scripts.length > 0) {
            const src = scripts[0].src;
            return src.substring(0, src.lastIndexOf('/js/')) + '/audio/';
        }
        // Fallback: try to detect from page location
        const path = window.location.pathname;
        const base = path.substring(0, path.indexOf('/', 1) + 1);
        return base + 'assets/audio/';
    })();

    function getCtx() {
        if (!audioCtx) {
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            } catch (e) {
                console.warn('[Audio] Cannot create AudioContext yet:', e.message);
                return null;
            }
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            // Resume() must be called from a user gesture; we try anyway
            // and silently ignore if the browser refuses.
            audioCtx.resume().catch(() => {});
        }
        return audioCtx;
    }

    // ============================================================
    //  PRELOAD AUDIO BUFFERS
    //  Fetches and decodes both chime files on first use
    //  v5.1.1: We no longer try to create AudioContext on page load
    //  (Chrome blocks this). Instead we just fetch the raw audio
    //  bytes; the AudioContext is only created on first user gesture.
    // ============================================================
    async function loadBuffers() {
        if (buffersLoaded || buffersLoading) return;
        buffersLoading = true;

        // v5.1.1: Fetch the audio files as ArrayBuffers WITHOUT needing
        // an AudioContext. decodeAudioData() does require an AudioContext,
        // so we defer decoding until first playback (see dingDong / emergencyAlert).
        try {
            const [resp1, resp2] = await Promise.all([
                fetch(AUDIO_BASE + 'chime-general.mp4'),
                fetch(AUDIO_BASE + 'chime-code.mp4')
            ]);

            if (!resp1.ok || !resp2.ok) throw new Error('HTTP ' + resp1.status + '/' + resp2.status);

            generalChimeRaw = await resp1.arrayBuffer();
            codeChimeRaw = await resp2.arrayBuffer();
            console.log('[Audio] Chime files fetched (raw): general=' +
                generalChimeRaw.byteLength + 'B, code=' +
                codeChimeRaw.byteLength + 'B');
        } catch (e) {
            console.warn('[Audio] Could not fetch chime files, will use synthesized fallback:', e.message);
        } finally {
            buffersLoading = false;
        }
    }

    // v5.1.1: Decode raw buffers on first playback (requires AudioContext).
    async function ensureDecoded() {
        if (buffersLoaded) return true;
        const ctx = getCtx();
        if (!ctx) return false; // AudioContext not yet available
        try {
            if (generalChimeRaw && !generalChimeBuffer) {
                generalChimeBuffer = await ctx.decodeAudioData(generalChimeRaw.slice(0));
            }
            if (codeChimeRaw && !codeChimeBuffer) {
                codeChimeBuffer = await ctx.decodeAudioData(codeChimeRaw.slice(0));
            }
            if (generalChimeBuffer && codeChimeBuffer) {
                buffersLoaded = true;
                console.log('[Audio] Chime buffers decoded — general:',
                    generalChimeBuffer.duration.toFixed(1) + 's, code:',
                    codeChimeBuffer.duration.toFixed(1) + 's');
                return true;
            }
        } catch (e) {
            console.warn('[Audio] Could not decode chime buffers:', e.message);
        }
        return false;
    }

    // Start fetching raw audio bytes immediately (no AudioContext needed).
    if (document.readyState === 'complete') {
        loadBuffers();
    } else {
        window.addEventListener('load', loadBuffers);
    }
    // v5.1.1: Create the AudioContext on the FIRST user gesture.
    // This is required by Chrome's autoplay policy.
    function unlockAudio() {
        const ctx = getCtx();
        if (ctx && ctx.state === 'suspended') ctx.resume();
        // Also try to decode any pending buffers now that we have a context.
        ensureDecoded();
    }
    // Listen for the first user gesture (any of these events counts).
    ['click', 'touchstart', 'keydown', 'pointerdown'].forEach(evt => {
        document.addEventListener(evt, unlockAudio, { once: true, passive: true });
    });

    // ============================================================
    //  PLAY AUDIO BUFFER (only first N seconds)
    //  Plays a portion of a decoded audio buffer
    // ============================================================
    function playBuffer(buffer, maxDuration, onComplete) {
        const ctx = getCtx();
        const source = ctx.createBufferSource();
        source.buffer = buffer;

        // Create gain node for smooth fade-out at the end
        const gainNode = ctx.createGain();
        gainNode.gain.setValueAtTime(1.0, ctx.currentTime);

        const playDuration = Math.min(maxDuration, buffer.duration);

        // Fade out in the last 0.5 seconds to avoid click
        const fadeStart = Math.max(0, playDuration - 0.5);
        gainNode.gain.setValueAtTime(1.0, ctx.currentTime + fadeStart);
        gainNode.gain.linearRampToValueAtTime(0.0, ctx.currentTime + playDuration);

        source.connect(gainNode);
        gainNode.connect(ctx.destination);

        source.start(0, 0, playDuration + 0.1);

        if (onComplete) {
            setTimeout(onComplete, playDuration * 1000);
        }
    }

    // ============================================================
    //  BELL TONE — fallback synthesized tone (kept for compatibility)
    // ============================================================
    function bellTone(freq, startTime, duration, volume) {
        const ctx = getCtx();
        const t = ctx.currentTime + startTime;
        const vol = volume || 1.0;

        const osc = ctx.createOscillator();
        const osc2 = ctx.createOscillator();
        const osc3 = ctx.createOscillator();

        osc.type = 'sine';
        osc2.type = 'sine';
        osc3.type = 'triangle';

        osc.frequency.setValueAtTime(freq, t);
        osc.frequency.exponentialRampToValueAtTime(freq * 0.998, t + duration);

        osc2.frequency.setValueAtTime(freq * 2.0, t);
        osc2.frequency.exponentialRampToValueAtTime(freq * 1.997, t + duration);

        osc3.frequency.setValueAtTime(freq * 4.0, t);

        const gain1 = ctx.createGain();
        const gain2 = ctx.createGain();
        const gain3 = ctx.createGain();

        gain1.gain.setValueAtTime(0, t);
        gain1.gain.linearRampToValueAtTime(0.32 * vol, t + 0.006);
        gain1.gain.setValueAtTime(0.32 * vol, t + 0.01);
        gain1.gain.exponentialRampToValueAtTime(0.16 * vol, t + duration * 0.08);
        gain1.gain.exponentialRampToValueAtTime(0.08 * vol, t + duration * 0.27);
        gain1.gain.exponentialRampToValueAtTime(0.04 * vol, t + duration * 0.65);
        gain1.gain.exponentialRampToValueAtTime(0.001, t + duration);

        gain2.gain.setValueAtTime(0, t);
        gain2.gain.linearRampToValueAtTime(0.12 * vol, t + 0.006);
        gain2.gain.exponentialRampToValueAtTime(0.05 * vol, t + duration * 0.10);
        gain2.gain.exponentialRampToValueAtTime(0.02 * vol, t + duration * 0.35);
        gain2.gain.exponentialRampToValueAtTime(0.001, t + duration * 0.7);

        gain3.gain.setValueAtTime(0, t);
        gain3.gain.linearRampToValueAtTime(0.03 * vol, t + 0.005);
        gain3.gain.exponentialRampToValueAtTime(0.001, t + duration * 0.20);

        const delay = ctx.createDelay(0.5);
        delay.delayTime.value = 0.045;
        const feedback = ctx.createGain();
        feedback.gain.value = 0.22;
        const dGain = ctx.createGain();
        dGain.gain.value = 0.12;

        const master = ctx.createGain();
        master.gain.value = 0.85;

        [gain1, gain2, gain3].forEach(g => g.connect(master));
        gain1.connect(delay);
        delay.connect(feedback);
        feedback.connect(delay);
        delay.connect(dGain);
        dGain.connect(master);
        master.connect(ctx.destination);

        osc.connect(gain1);  osc.start(t);  osc.stop(t + duration + 0.2);
        osc2.connect(gain2); osc2.start(t); osc2.stop(t + duration * 0.75);
        osc3.connect(gain3); osc3.start(t); osc3.stop(t + duration * 0.3);
    }

    // ============================================================
    //  SYNTHESIZED FALLBACK CHIMES
    //  Used only when audio files haven't loaded yet
    // ============================================================
    function synthGeneralChime(onComplete) {
        bellTone(246.94, 0.00, 3.2, 0.85);
        bellTone(311.13, 0.30, 2.9, 1.00);
        bellTone(392.00, 0.60, 2.8, 0.90);
        bellTone(493.88, 0.90, 2.5, 0.88);
        bellTone(392.00, 1.20, 2.3, 0.70);
        if (onComplete) setTimeout(onComplete, 3500);
    }

    function synthCodeChime(onComplete) {
        bellTone(196.00, 0.00, 0.40, 0.75);
        bellTone(293.66, 0.10, 0.40, 0.80);
        bellTone(246.94, 0.20, 0.40, 0.75);
        bellTone(622.25, 0.30, 0.40, 0.85);
        bellTone(293.66, 0.40, 0.40, 0.75);
        bellTone(1244.51, 0.50, 0.35, 0.65);
        bellTone(277.18, 0.60, 0.40, 0.75);
        bellTone(493.88, 0.70, 0.60, 0.85);
        bellTone(554.37, 0.90, 1.5, 0.90);
        bellTone(440.00, 1.60, 2.5, 0.80);
        if (onComplete) setTimeout(onComplete, 4200);
    }

    // ============================================================
    //  GENERAL CALL CHIME (Reference File 1)
    //  Plays beginning tone of chime-general.mp4
    //  For: employee calls, department calls, general announcements
    // ============================================================
    async function dingDong(onComplete) {
        // v5.1.1: Ensure AudioContext is created (from user gesture) and
        // raw buffers are decoded before playback.
        await ensureDecoded();
        if (generalChimeBuffer) {
            playBuffer(generalChimeBuffer, GENERAL_CHIME_DURATION, onComplete);
        } else {
            // Fallback to synthesized if file not loaded
            synthGeneralChime(onComplete);
        }
    }

    // Keep airportChime as alias for backward compatibility
    function airportChime(startOffset, onComplete) {
        // startOffset is ignored when using real audio files
        dingDong(onComplete);
    }

    // ============================================================
    //  EMERGENCY CODE CHIME (Reference File 2)
    //  Plays beginning tone of chime-code.mp4
    //  For: Code Blue, Code Red, Code Pink, Code Black, etc.
    // ============================================================
    async function emergencyAlert(onComplete) {
        // v5.1.1: Ensure AudioContext is created (from user gesture) and
        // raw buffers are decoded before playback.
        await ensureDecoded();
        if (codeChimeBuffer) {
            playBuffer(codeChimeBuffer, CODE_CHIME_DURATION, onComplete);
        } else {
            // Fallback to synthesized if file not loaded
            synthCodeChime(onComplete);
        }
    }

    // ============================================================
    //  PHRASE SPLITTER
    // ============================================================
    function splitPhrases(text) {
        return text
            .replace(/\.\.\./g, '|||')
            .replace(/…/g, '|||')
            .replace(/,\s+/g, '|||')
            .replace(/\.\s+([A-Z])/g, '.||| $1')
            .replace(/\.\s+([ء-ي])/g, '.||| $1')
            .split('|||')
            .map(s => s.trim())
            .filter(s => s.length > 0);
    }

    // ============================================================
    //  SPEAK CHUNK (single utterance)
    // ============================================================
    function speakChunk(text, voice, rate, pitch, lang, onEnd) {
        if (!window.speechSynthesis) { if (onEnd) onEnd(); return; }
        const u = new SpeechSynthesisUtterance(text);
        u.lang = lang || 'en-US';
        u.rate = rate;
        u.pitch = pitch;
        u.volume = 1.0;
        if (voice) u.voice = voice;
        u.onend = () => { if (onEnd) onEnd(); };
        u.onerror = () => { if (onEnd) onEnd(); };
        window.speechSynthesis.speak(u);
    }

    function speakPhrases(phrases, voice, rate, pitch, pauseMs, lang, onAllDone) {
        let idx = 0;
        function next() {
            if (idx >= phrases.length) { if (onAllDone) onAllDone(); return; }
            const phrase = phrases[idx++];
            speakChunk(phrase, voice, rate, pitch, lang, () => {
                if (idx < phrases.length) {
                    setTimeout(next, pauseMs);
                } else {
                    if (onAllDone) onAllDone();
                }
            });
        }
        next();
    }

    // ============================================================
    //  VOICE PICKER (English + Arabic)
    // ============================================================
    function pickVoice(gender, lang) {
        const voices = window.speechSynthesis.getVoices();
        const isAr = lang && lang.startsWith('ar');
        const langPrefix = isAr ? 'ar' : 'en';
        const filtered = voices.filter(v => v.lang.startsWith(langPrefix));

        if (!filtered.length) {
            // Try Google voices as last resort — they often support both languages
            const googleVoices = voices.filter(v => v.name.toLowerCase().includes('google'));
            if (googleVoices.length) {
                const arGoogle = googleVoices.find(v => v.lang.startsWith('ar'));
                if (isAr && arGoogle) return arGoogle;
                const enGoogle = googleVoices.find(v => v.lang.startsWith('en'));
                if (!isAr && enGoogle) return enGoogle;
            }
            const fallback = voices.filter(v => v.lang.startsWith(isAr ? 'ar' : 'en'));
            if (fallback.length) return fallback[0];
            return voices.length ? voices[0] : null;
        }

        if (isAr) {
            const arNames = gender === 'male'
                ? ['majed', 'maged', 'tarik', 'hadi', 'male', 'naayf']
                : ['laila', 'maryam', 'zeina', 'hoda', 'amira', 'female', 'zariyah'];
            const match = filtered.find(v => arNames.some(n => v.name.toLowerCase().includes(n.toLowerCase())));
            if (match) return match;
            return gender === 'male' ? filtered[0] : (filtered[1] || filtered[0]);
        }

        const maleNames = ['david', 'james', 'mark', 'guy', 'daniel', 'alex', 'george', 'richard', 'matthew', 'male'];
        const femaleNames = ['zira', 'samantha', 'karen', 'susan', 'victoria', 'olivia', 'aria', 'jenny', 'natasha', 'hazel', 'female'];
        const names = gender === 'male' ? maleNames : femaleNames;
        const match = filtered.find(v => names.some(n => v.name.toLowerCase().includes(n)));
        if (match) return match;
        return gender === 'male' ? filtered[0] : (filtered[1] || filtered[0]);
    }

    // ============================================================
    //  READ AUDIO SETTINGS
    //  v5.1: Improved defaults for clearer, more natural speech
    //  - Speech rate: 0.85 (more natural than 0.62 airport-style)
    //  - Female pitch: 1.05 (clearer than 1.10)
    //  - Pause between phrases: 250ms (shorter than 700ms)
    //  - Repeat: 2 times (kept)
    // ============================================================
    function getAudioSettings() {
        const rate   = parseFloat(document.getElementById('sRt')?.value || document.getElementById('sRt2')?.value || '0.85');
        const pitchM = parseFloat(document.getElementById('sPM')?.value || document.getElementById('sPM2')?.value || '0.85');
        const pitchF = parseFloat(document.getElementById('sPF')?.value || document.getElementById('sPF2')?.value || '1.05');
        const repeat = parseInt(document.getElementById('sRpt')?.value || '2');
        // v5.1: shorter pause between phrases (250ms instead of 700ms)
        const pauseMs = parseInt(document.getElementById('sPause')?.value || '250');
        return { rate, pitchM, pitchF, repeat, pauseMs };
    }

    // ============================================================
    //  SPEAK — Bilingual (English + Arabic)
    //  v5.1 changes:
    //   - Removed duplicate "Attention please" (was x2, now x0 for emergency)
    //   - Enabled Arabic speech synthesis (was previously skipped)
    //   - Respects gender parameter for voice selection
    //   - Uses effective pitch per gender (no longer locked to female pitch)
    // ============================================================
    function speak(text, gender, isEmergency, onDone, lang) {
        if (!window.speechSynthesis) { if (onDone) onDone(); return; }
        window.speechSynthesis.cancel();

        const speechLang = lang || 'en-US';
        // v5.1: Arabic speech is now supported (no longer skipped)
        // Note: if no Arabic voice is available, browser may fall back to default voice

        const { rate, pitchM, pitchF, repeat, pauseMs } = getAudioSettings();

        // v5.1: Use the requested gender instead of always female
        const useGender = gender || 'female';
        const effectivePitch = useGender === 'male' ? pitchM : pitchF;
        const voice = pickVoice(useGender, speechLang);

        const fullText = (typeof fixPronunciation === 'function') ? fixPronunciation(text) : text;
        const mainPhrases = splitPhrases(fullText);

        // v5.1: Removed "Attention please" duplication entirely
        // Previously: emergency codes had "Attention please" x2 prepended
        // Now: speak the announcement directly (faster, less repetitive)
        const allPhrases = mainPhrases;

        let runCount = 0;
        function doRun() {
            speakPhrases(allPhrases, voice, rate, effectivePitch, pauseMs, speechLang, () => {
                runCount++;
                if (runCount < repeat) {
                    // v5.1: Shorter pause between repeats (was 1200ms)
                    setTimeout(doRun, 600);
                } else {
                    hideAllSpeaking();
                    if (onDone) onDone();
                }
            });
        }
        // v5.1: Shorter initial delay (was 200ms)
        setTimeout(doRun, 100);
    }

    // ============================================================
    //  ANNOUNCE — Bilingual sequence
    //  v5.1: Supports both English and Arabic speech
    //  Emergency/Code: code-chime (ref2) → speech → code-chime (ref2)
    //  Normal:         general-chime (ref1) → speech → general-chime (ref1)
    //  If textAr is provided AND Arabic voice is available, speak Arabic
    //  after English; otherwise speak English only.
    // ============================================================
    function announce(textEn, gender, type, onDone, textAr) {
        cancelSpeech();
        const isEmergency = (type === 'emergency' || type === 'code');

        function speakEnglish(cb) {
            if (textEn && textEn.trim()) {
                speak(textEn, gender, isEmergency, cb, 'en-US');
            } else {
                if (cb) cb();
            }
        }

        function speakArabic(cb) {
            // v5.1: Speak Arabic if provided and language preference includes it
            // Check if user language is Arabic OR if both English and Arabic are empty
            const userLangAr = (typeof LANG !== 'undefined') && LANG === 'ar';
            if (textAr && textAr.trim() && (userLangAr || !textEn || !textEn.trim())) {
                speak(textAr, gender, isEmergency, cb, 'ar-SA');
            } else {
                if (cb) cb();
            }
        }

        // After initial chime: speak English, then Arabic, then play chime again
        function afterChime() {
            speakEnglish(() => {
                speakArabic(() => {
                    if (isEmergency) {
                        emergencyAlert(() => {
                            if (onDone) onDone();
                        });
                    } else {
                        dingDong(() => {
                            if (onDone) onDone();
                        });
                    }
                });
            });
        }

        if (isEmergency) {
            // Emergency/Code: play CODE chime (ref2) → then speech
            emergencyAlert(afterChime);
        } else {
            // Normal: play GENERAL chime (ref1) → then speech
            dingDong(afterChime);
        }
    }

    function cancelSpeech() {
        if (window.speechSynthesis) window.speechSynthesis.cancel();
    }

    function hideAllSpeaking() {
        document.querySelectorAll('.speaking-indicator').forEach(el => el.classList.remove('active'));
    }

    if (window.speechSynthesis) {
        window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
        window.speechSynthesis.getVoices();
    }

    return {
        announce,
        speak,
        bellTone,
        dingDong,
        emergencyAlert,
        cancelSpeech,
        pickVoice,
    };
})();
