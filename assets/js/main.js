    
      // ===== DATA =====
      const LOCS = [
        { c: "ER", n: "Emergency Room" },
        { c: "ICU", n: "Intensive Care Unit" },
        { c: "CCU", n: "Coronary Care Unit" },
        { c: "NICU", n: "Neonatal ICU" },
        { c: "MMW", n: "Male Medical Ward" },
        { c: "FMW", n: "Female Medical Ward" },
        { c: "OR", n: "Operating Room" },
        { c: "RAD", n: "Radiology Department" },
        { c: "LAB", n: "Laboratory" },
        { c: "DLY", n: "Dialysis Unit" },
        { c: "OPC", n: "Outpatient Clinics" },
        { c: "ADM", n: "Administration" },
        { c: "LOB", n: "Main Lobby" },
      ];
      const SPECS = [
        "Internal Medicine",
        "Cardiology",
        "Neurology",
        "Neurosurgery",
        "Gastroenterology",
        "Endocrinology",
        "General Surgery",
        "Orthopedic Surgery",
        "Urology",
        "Pediatrics",
        "Obstetrics and Gynecology",
        "Anesthesia",
      ];
      const ROLES = [
        "Hospital Director On Call",
        "Administrative Supervisor",
        "Security Supervisor",
        "Maintenance Supervisor",
        "IT Support",
        "Nursing Supervisor",
        "Head Nurse",
        "Laboratory Technician",
        "Radiology Technician",
        "Respiratory Therapist",
        "OR Technician",
        "Dialysis Technician",
      ];
      const CODES = [
        {
          id: "CODE_BLUE",
          n: "Code Blue",
          d: "Cardiac Arrest",
          cl: "#fff",
          bg: "#1a56db",
          ic: "fa-heart-pulse",
          msg: "Code Blue, Code Blue, {loc}. Medical emergency team, respond immediately.",
        },
        {
          id: "CODE_RED",
          n: "Code Red",
          d: "Fire Emergency",
          cl: "#fff",
          bg: "#e02424",
          ic: "fa-fire",
          msg: "Code Red, Code Red, {loc}. All staff follow fire emergency protocol immediately.",
        },
        {
          id: "CODE_WHITE",
          n: "Code White",
          d: "Violent Person",
          cl: "#111827",
          bg: "#f3f4f6",
          ic: "fa-shield-halved",
          msg: "Code White, Code White, {loc}. Security team, respond immediately.",
        },
        {
          id: "CODE_PINK",
          n: "Code Pink",
          d: "Infant Abduction",
          cl: "#fff",
          bg: "#db2777",
          ic: "fa-baby",
          msg: "Code Pink, Code Pink. Infant abduction alert. All exits are secured. Security, respond immediately.",
        },
        {
          id: "CODE_BLACK",
          n: "Code Black",
          d: "Bomb Threat",
          cl: "#fff",
          bg: "#111827",
          ic: "fa-skull-crossbones",
          msg: "Code Black, Code Black. Bomb threat received. Follow evacuation protocol immediately.",
        },
        {
          id: "CODE_YELLOW",
          n: "Code Yellow",
          d: "Missing Patient",
          cl: "#111827",
          bg: "#f59f00",
          ic: "fa-magnifying-glass",
          msg: "Code Yellow, Code Yellow. Missing patient alert at {loc}. All staff, be on alert.",
        },
        {
          id: "RRT_TEAM",
          n: "RRT Team",
          d: "Rapid Response",
          cl: "#fff",
          bg: "#7c3aed",
          ic: "fa-truck-medical",
          msg: "Rapid Response Team required at {loc}. R R T team, respond immediately.",
        },
      ];
      const TMPLS = [
        "Doctor [Name], [Specialty] on call, please contact the [Department], extension [EXT].",
        "Attention [Staff Role], please report to [Location] immediately, extension [EXT].",
        "Attention please: A visitor is requested at [Department]. Kindly proceed to the information desk.",
        "General announcement for all staff: [Your message here].",
      ];
      const CODE_ACTIONS = {
        CODE_BLUE: "Crash team respond immediately, bring crash cart",
        CODE_RED: "Evacuate, call fire department, use extinguishers",
        CODE_WHITE: "Security contain situation, do not approach alone",
        CODE_PINK: "Lock all exits, check persons leaving, call security",
        CODE_BLACK: "Do not touch, evacuate area, notify police immediately",
        CODE_YELLOW: "Search all areas, check CCTV, notify security",
        RRT_TEAM: "RRT team respond with equipment to stated location",
      };

      // ===== STATE =====
      let CU = null,
        LOC = { c: "ER", n: "Emergency Room" },
        LOGS = [],
        SBC = false;
      let VS = {
        q: "female",
        cb: "female",
        st: "male",
        st2: "male",
        ca: "female",
        dr: "female",
        fc: "female",
      };
      let ST = { t: 0, e: 0, d: 0, s: 0, c: 0 };

      // ===== INIT =====
      document.addEventListener("DOMContentLoaded", () => {
        tick();
        setInterval(tick, 1000);
        fillDrops();
        renderGrids();
        renderRef();
        renderTmpls();
        if (window.speechSynthesis) {
          window.speechSynthesis.onvoiceschanged = () =>
            window.speechSynthesis.getVoices();
          window.speechSynthesis.getVoices();
        }
      });

      function tick() {
        const n = new Date();
        const el = document.getElementById("clk");
        if (el)
          el.textContent = n.toLocaleTimeString("en-US", { hour12: false });
        const h = n.getHours();
        const g =
          h < 12 ? "Good morning" : h < 17 ? "Good afternoon" : "Good evening";
        const dg = document.getElementById("dgr");
        if (dg && CU)
          dg.textContent =
            g + ", " + CU.name.split(" ")[0] + " — King Khalid Hospital";
      }

      function fillDrops() {
        ["qSp", "cbSp", "drSp"].forEach((id) => {
          const e = document.getElementById(id);
          if (e)
            e.innerHTML = SPECS.map((s) => "<option>" + s + "</option>").join(
              "",
            );
        });
        ["cbFr", "drFr"].forEach((id) => {
          const e = document.getElementById(id);
          if (e)
            e.innerHTML = LOCS.map(
              (l) => '<option value="' + l.c + '">' + l.n + "</option>",
            ).join("");
        });
        ["cbSR", "stRl"].forEach((id) => {
          const e = document.getElementById(id);
          if (e)
            e.innerHTML = ROLES.map((r) => "<option>" + r + "</option>").join(
              "",
            );
        });
        ["cbSL", "stLc"].forEach((id) => {
          const e = document.getElementById(id);
          if (e)
            e.innerHTML = LOCS.map(
              (l) => '<option value="' + l.c + '">' + l.n + "</option>",
            ).join("");
        });
      }

      function renderGrids() {
        ["cgH", "cgCB", "cgEM"].forEach((gid) => {
          const g = document.getElementById(gid);
          if (!g) return;
          g.innerHTML = CODES.map(
            (c) =>
              '<button class="cbtn" style="background:' +
              c.bg +
              ";color:" +
              c.cl +
              '" onclick="actCode(\'' +
              c.id +
              '\')"><i class="fas ' +
              c.ic +
              '"></i><span class="cn">' +
              c.n +
              '</span><span class="cd">' +
              c.d +
              "</span></button>",
          ).join("");
        });
      }

      function renderRef() {
        const tb = document.getElementById("codeRef");
        if (!tb) return;
        tb.innerHTML = CODES.map(
          (c) =>
            '<tr><td><span style="display:inline-flex;align-items:center;gap:8px"><span style="width:13px;height:13px;border-radius:50%;background:' +
            c.bg +
            ';display:inline-block;border:1px solid #ddd"></span><strong>' +
            c.n +
            "</strong></span></td><td>" +
            c.d +
            '</td><td style="font-size:.8rem;color:var(--text-muted)">' +
            (CODE_ACTIONS[c.id] || "") +
            "</td></tr>",
        ).join("");
      }

      function renderTmpls() {
        const el = document.getElementById("tmplBtns");
        if (!el) return;
        el.innerHTML = ["Doctor Call", "Staff Alert", "Visitor", "General"]
          .map(
            (t, i) =>
              '<button onclick="insT(' +
              i +
              ')" style="background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:5px 10px;font-size:.73rem;cursor:pointer">' +
              t +
              "</button>",
          )
          .join("");
      }

      function insT(i) {
        const e = document.getElementById("fcMsg");
        if (e) e.value = TMPLS[i];
      }

      // ===== AUTH =====
      function showPage(p) {
        document
          .querySelectorAll(".page")
          .forEach((x) => x.classList.remove("active"));
        document.getElementById("page-" + p).classList.add("active");
      }

      const DEMO_USERS = [
        {
          id: 1,
          name: "System Administrator",
          email: "admin@hospital.sa",
          password: "Admin@1234",
          role: "admin",
          gender: "male",
        },
        {
          id: 2,
          name: "Dr. Sara Al-Rashidi",
          email: "sara@hospital.sa",
          password: "Admin@1234",
          role: "operator",
          gender: "female",
        },
        {
          id: 3,
          name: "Mohammed Al-Qahtani",
          email: "mohammed@hospital.sa",
          password: "Admin@1234",
          role: "operator",
          gender: "male",
        },
      ];

      function doLogin() {
        const em = document.getElementById("lEmail").value.trim();
        const pw = document.getElementById("lPass").value;
        if (!em || !pw) {
          toast("Please fill all fields", "error", "Login");
          return;
        }
        const u = DEMO_USERS.find((x) => x.email === em && x.password === pw);
        if (!u) {
          toast("Invalid email or password", "error", "Login Failed");
          return;
        }
        CU = u;
        updateUI();
        showPage("dashboard");
        showTab("home");
        toast("Welcome, " + u.name.split(" ")[0] + "!", "success", "Logged In");
      }

      function doRegister() {
        const n = document.getElementById("rName").value.trim();
        const em = document.getElementById("rEmail").value.trim();
        const pw = document.getElementById("rPass").value;
        if (!n || !em || !pw) {
          toast("Fill all required fields", "error", "Register");
          return;
        }
        if (pw.length < 6) {
          toast("Password min 6 characters", "error", "Register");
          return;
        }
        toast("Account created! Please sign in.", "success", "Registered");
        setTimeout(() => showPage("login"), 1200);
      }

      function doLogout() {
        CU = null;
        showPage("landing");
        toast("Signed out successfully", "info", "Logout");
      }

      function updateUI() {
        if (!CU) return;
        const ini = CU.name.charAt(0).toUpperCase();
        ["sbAv", "tbAv", "setAv"].forEach((id) => {
          const e = document.getElementById(id);
          if (e) e.textContent = ini;
        });
        const sn = document.getElementById("sbName");
        if (sn) sn.textContent = CU.name.split(" ").slice(0, 2).join(" ");
        const sr = document.getElementById("sbRole");
        if (sr) sr.textContent = CU.role.toUpperCase();
        const sN = document.getElementById("setNm");
        if (sN) sN.textContent = CU.name;
        const sE = document.getElementById("setEm");
        if (sE) sE.textContent = CU.email;
        const sR = document.getElementById("setRl");
        if (sR) sR.textContent = CU.role;
      }

      // ===== SIDEBAR =====
      function toggleSB() {
        SBC = !SBC;
        document.getElementById("SB").classList.toggle("col", SBC);
        document.getElementById("MW").classList.toggle("col", SBC);
        const ti = document.getElementById("sbTI");
        if (ti)
          ti.className = SBC ? "fas fa-chevron-right" : "fas fa-chevron-left";
      }

      // ===== TABS =====
      function showTab(t) {
        document
          .querySelectorAll(".tab")
          .forEach((x) => x.classList.remove("active"));
        document
          .querySelectorAll(".ni")
          .forEach((x) => x.classList.remove("active"));
        const el = document.getElementById("tab-" + t);
        if (el) el.classList.add("active");
        document.querySelectorAll(".ni").forEach((x) => {
          if (
            x.getAttribute("onclick") &&
            x.getAttribute("onclick").includes("'" + t + "'")
          )
            x.classList.add("active");
        });
        if (t === "analytics") updAnalytics();
      }

      // ===== LOCATION =====
      function openLoc() {
        const ll = document.getElementById("loList");
        if (ll)
          ll.innerHTML = LOCS.map(
            (l) =>
              '<div class="lo' +
              (l.c === LOC.c ? " sel" : "") +
              '" onclick="selLoc(\'' +
              l.c +
              "','" +
              l.n +
              '\')"><i class="fas fa-map-marker-alt"></i> ' +
              l.n +
              "</div>",
          ).join("");
        document.getElementById("locOv").classList.add("show");
      }
      function closeLoc() {
        document.getElementById("locOv").classList.remove("show");
      }
      function selLoc(c, n) {
        LOC = { c, n };
        ["locLabel", "cbLoc", "emLoc", "hLoc"].forEach((id) => {
          const e = document.getElementById(id);
          if (e) e.textContent = n;
        });
        closeLoc();
        toast("Location: " + n, "info", "Location Set");
      }

      // ===== VOICE TOGGLE =====
      function sv(panel, g) {
        VS[panel] = g;
        const map = {
          q: ["vmQ", "vfQ"],
          cb: ["vmCB", "vfCB"],
          st: ["vmST", "vfST"],
          st2: ["vmST2", "vfST2"],
          ca: ["vmCA", "vfCA"],
          dr: ["vmDR", "vfDR"],
          fc: ["vmFC", "vfFC"],
        };
        const [m, f] = map[panel] || [];
        if (m) {
          const e = document.getElementById(m);
          if (e) e.classList.toggle("act", g === "male");
        }
        if (f) {
          const e = document.getElementById(f);
          if (e) e.classList.toggle("act", g === "female");
        }
      }

      // ===== TTS =====
      function speak(txt, g, cb) {
        if (!window.speechSynthesis) {
          if (cb) cb();
          return;
        }
        window.speechSynthesis.cancel();
        const rep = parseInt(document.getElementById("sRpt")?.value || "2");
        const rate = parseFloat(
          document.getElementById("sRt")?.value || "0.85",
        );
        const pitchM = parseFloat(
          document.getElementById("sPM")?.value || "0.8",
        );
        const pitchF = parseFloat(
          document.getElementById("sPF")?.value || "1.2",
        );
        let cnt = 0;
        function say() {
          const u = new SpeechSynthesisUtterance(txt);
          u.lang = "en-US";
          u.rate = rate;
          u.pitch = g === "male" ? pitchM : pitchF;
          const vs = window.speechSynthesis.getVoices();
          const en = vs.filter((v) => v.lang.startsWith("en"));
          if (en.length > 0) {
            const gv = en.filter((v) => {
              const nl = v.name.toLowerCase();
              return g === "male"
                ? nl.includes("david") ||
                    nl.includes("mark") ||
                    nl.includes("james") ||
                    nl.includes("guy") ||
                    nl.includes("male")
                : nl.includes("zira") ||
                    nl.includes("samantha") ||
                    nl.includes("karen") ||
                    nl.includes("susan") ||
                    nl.includes("victoria") ||
                    nl.includes("female");
            });
            u.voice =
              gv.length > 0
                ? gv[0]
                : en[g === "male" ? 0 : Math.min(1, en.length - 1)];
          }
          u.onend = () => {
            cnt++;
            if (cnt < rep) setTimeout(say, 600);
            else if (cb) cb();
          };
          window.speechSynthesis.speak(u);
        }
        say();
      }

      // ===== EMERGENCY =====
      function actCode(id) {
        const c = CODES.find((x) => x.id === id);
        if (!c) return;
        const msg = c.msg.replace("{loc}", LOC.n);
        showAnn(c.n, msg, c.bg, c.cl, c.ic);
        speak(msg, "female");
        addLog("emergency_code", c.n, msg, LOC.n, "female", c.bg);
        ST.t++;
        ST.e++;
        updStats();
        const ef = document.getElementById("emFeed");
        if (ef) {
          if (ef.innerHTML.includes("No emergency")) ef.innerHTML = "";
          const now = new Date().toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
          });
          ef.insertAdjacentHTML(
            "afterbegin",
            '<li class="cl-item"><span class="cl-time">' +
              now +
              '</span><span class="cl-dot" style="background:' +
              c.bg +
              '"></span><span class="cl-txt">' +
              c.n +
              " — " +
              LOC.n +
              "</span></li>",
          );
        }
        toast(c.n + " activated — " + LOC.n, "warning", "Emergency");
      }

      // ===== DOCTOR CALL =====
      function callDoc(src) {
        let sp, lv, ex, fr, g;
        if (src === "q") {
          sp = document.getElementById("qSp").value;
          lv = document.getElementById("qLv").value;
          ex = document.getElementById("qEx").value || "[ext]";
          fr = LOC.n;
          g = VS.q;
        } else if (src === "cb") {
          sp = document.getElementById("cbSp").value;
          lv = document.getElementById("cbLv").value;
          ex = document.getElementById("cbEx").value || "[ext]";
          fr =
            LOCS.find((l) => l.c === document.getElementById("cbFr").value)
              ?.n || LOC.n;
          g = VS.cb;
        } else {
          sp = document.getElementById("drSp").value;
          lv = document.getElementById("drLv").value;
          ex = document.getElementById("drEx").value || "[ext]";
          fr =
            LOCS.find((l) => l.c === document.getElementById("drFr").value)
              ?.n || LOC.n;
          g = VS.dr;
        }
        const msg =
          sp +
          " " +
          lv +
          " on call, please contact the " +
          fr +
          ", extension " +
          ex +
          ". " +
          sp +
          " " +
          lv +
          " on call, please contact the " +
          fr +
          ", extension " +
          ex +
          ".";
        showAnn(sp + " " + lv, msg, "#1a56db", "#fff", "fa-user-md");
        speak(msg, g, () => hideSpk());
        showSpk(src === "cb" ? "spkD" : src === "dr" ? "spkDR" : null);
        addLog("call_doctor", "Doctor Page", msg, fr, g, "#1a56db");
        ST.t++;
        ST.d++;
        updStats();
        const tb = document.getElementById("drLog");
        if (tb) {
          if (tb.innerHTML.includes("No doctor")) tb.innerHTML = "";
          const now = new Date().toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
          });
          tb.insertAdjacentHTML(
            "afterbegin",
            "<tr><td>" +
              now +
              '</td><td><span class="bdg" style="background:rgba(26,86,219,.1);color:#1a56db">' +
              sp +
              "</span></td><td>" +
              lv +
              '</td><td style="font-weight:700">' +
              ex +
              '</td><td><i class="fas fa-' +
              (g === "male" ? "mars" : "venus") +
              '" style="color:' +
              (g === "male" ? "#1a56db" : "#db2777") +
              '"></i> ' +
              g +
              "</td></tr>",
          );
        }
        toast("Paging " + sp + " " + lv, "info", "Doctor Page");
      }

      // ===== STAFF CALL =====
      function callSt() {
        const rl = document.getElementById("cbSR").value;
        const ex = document.getElementById("cbSE").value || "[ext]";
        const lc =
          LOCS.find((l) => l.c === document.getElementById("cbSL").value)?.n ||
          LOC.n;
        const g = VS.st;
        const msg =
          "Attention, " +
          rl +
          ". " +
          rl +
          ", please contact the " +
          lc +
          ", extension " +
          ex +
          ".";
        showAnn(rl, msg, "#059669", "#fff", "fa-users");
        speak(msg, g, () => hideSpk());
        showSpk("spkS");
        addLog("call_staff", "Staff Call", msg, lc, g, "#059669");
        ST.t++;
        ST.s++;
        updStats();
        logStaff(rl, lc, ex, g);
        toast("Paging " + rl, "info", "Staff Call");
      }
      function callStFull() {
        const rl = document.getElementById("stRl").value;
        const ex = document.getElementById("stEx").value || "[ext]";
        const lc =
          LOCS.find((l) => l.c === document.getElementById("stLc").value)?.n ||
          LOC.n;
        const g = VS.st2;
        const msg =
          "Attention, " +
          rl +
          ". " +
          rl +
          ", please contact the " +
          lc +
          ", extension " +
          ex +
          ".";
        showAnn(rl, msg, "#059669", "#fff", "fa-users");
        speak(msg, g, () => hideSpk());
        showSpk("spkST2");
        addLog("call_staff", "Staff Call", msg, lc, g, "#059669");
        ST.t++;
        ST.s++;
        updStats();
        logStaff(rl, lc, ex, g);
        toast("Paging " + rl, "info", "Staff Call");
      }
      function logStaff(rl, lc, ex, g) {
        const tb = document.getElementById("stLog");
        if (!tb) return;
        if (tb.innerHTML.includes("No staff")) tb.innerHTML = "";
        const now = new Date().toLocaleTimeString("en-US", {
          hour: "2-digit",
          minute: "2-digit",
          hour12: false,
        });
        tb.insertAdjacentHTML(
          "afterbegin",
          "<tr><td>" +
            now +
            '</td><td><span class="bdg" style="background:rgba(47,158,68,.1);color:#2f9e44">' +
            rl +
            "</span></td><td>" +
            lc +
            '</td><td style="font-weight:700">' +
            ex +
            '</td><td><i class="fas fa-' +
            (g === "male" ? "mars" : "venus") +
            '" style="color:' +
            (g === "male" ? "#1a56db" : "#db2777") +
            '"></i> ' +
            g +
            "</td></tr>",
        );
      }

      // ===== CUSTOM =====
      function bcastCA() {
        const msg = document.getElementById("cbCA").value.trim();
        if (!msg) {
          toast("Enter a message first", "error", "Broadcast");
          return;
        }
        const g = VS.ca;
        showAnn("Custom Announcement", msg, "#7c3aed", "#fff", "fa-bullhorn");
        speak(msg, g, () => hideSpk());
        showSpk("spkC");
        addLog("custom", "Custom", msg, LOC.n, g, "#7c3aed");
        ST.t++;
        ST.c++;
        updStats();
        toast("Announcement broadcast", "success", "Broadcast");
      }
      function bcastFull() {
        const msg = document.getElementById("fcMsg").value.trim();
        if (!msg) {
          toast("Enter announcement text", "error", "Broadcast");
          return;
        }
        const g = VS.fc;
        showAnn("Custom Announcement", msg, "#7c3aed", "#fff", "fa-bullhorn");
        speak(msg, g, () => hideSpk());
        showSpk("spkFC");
        addLog("custom", "Custom", msg, LOC.n, g, "#7c3aed");
        ST.t++;
        ST.c++;
        updStats();
        const ch = document.getElementById("custHist");
        if (ch) {
          if (ch.innerHTML.includes("No custom")) ch.innerHTML = "";
          const now = new Date().toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
          });
          ch.insertAdjacentHTML(
            "afterbegin",
            '<li class="cl-item"><span class="cl-time">' +
              now +
              '</span><span class="cl-dot" style="background:#7c3aed"></span><span class="cl-txt" style="font-size:.81rem">' +
              msg.substring(0, 100) +
              (msg.length > 100 ? "..." : "") +
              "</span></li>",
          );
        }
        toast("Announcement broadcast", "success", "Broadcast");
      }

      // ===== ANNOUNCEMENT MODAL =====
      function showAnn(title, msg, bg, cl, ic) {
        document.getElementById("mTitle").textContent = title;
        document.getElementById("mMsg").textContent = msg;
        document.getElementById("mRing").style.cssText =
          "width:80px;height:80px;border-radius:50%;margin:0 auto 18px;display:flex;align-items:center;justify-content:center;font-size:2rem;background:" +
          bg +
          ";color:" +
          cl +
          ";box-shadow:0 0 0 10px " +
          bg +
          "33";
        document.getElementById("mIcon").className = "fas " + ic;
        const pf = document.getElementById("pFill");
        pf.style.animation = "none";
        pf.offsetHeight;
        pf.style.animation = "prog 3.5s linear";
        document.getElementById("annOv").classList.add("show");
        setTimeout(
          () => document.getElementById("annOv").classList.remove("show"),
          5500,
        );
      }
      function closeAnn() {
        document.getElementById("annOv").classList.remove("show");
        window.speechSynthesis && window.speechSynthesis.cancel();
      }

      // ===== SPEAKING INDICATOR =====
      function showSpk(id) {
        if (!id) return;
        const e = document.getElementById(id);
        if (e) e.classList.add("show");
      }
      function hideSpk() {
        document
          .querySelectorAll(".spk")
          .forEach((e) => e.classList.remove("show"));
      }

      // ===== LOGS =====
      function addLog(type, code, msg, loc, g, col) {
        const now = new Date();
        LOGS.unshift({
          id: LOGS.length + 1,
          type,
          code,
          msg,
          loc,
          g,
          col,
          time: now.toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
          }),
          ts: now,
        });
        updRC();
        updFeed();
        updAllLogs();
      }
      function updRC() {
        const el = document.getElementById("rcList");
        if (!el) return;
        if (LOGS.length === 0) {
          el.innerHTML =
            '<li style="padding:22px 0;text-align:center;color:var(--text-muted);font-size:.83rem"><i class="fas fa-satellite-dish" style="font-size:2rem;opacity:.2;display:block;margin-bottom:8px"></i>No calls yet</li>';
          return;
        }
        el.innerHTML = LOGS.slice(0, 10)
          .map(
            (l) =>
              '<li class="cl-item"><span class="cl-time">' +
              l.time +
              '</span><span class="cl-dot" style="background:' +
              l.col +
              '"></span><span class="cl-txt">' +
              l.code +
              " — " +
              l.loc +
              '</span><span class="bdg" style="background:' +
              l.col +
              "22;color:" +
              l.col +
              '">' +
              l.type.replace("_", " ") +
              "</span></li>",
          )
          .join("");
      }
      function updFeed() {
        const el = document.getElementById("cbFeed");
        if (!el) return;
        if (LOGS.length === 0) {
          el.innerHTML =
            '<li style="padding:22px;text-align:center;color:var(--text-muted)">Waiting...</li>';
          return;
        }
        el.innerHTML = LOGS.slice(0, 20)
          .map(
            (l) =>
              '<li class="cl-item"><span class="cl-time">' +
              l.time +
              '</span><span class="cl-dot" style="background:' +
              l.col +
              '"></span><span class="cl-txt" style="font-size:.8rem">' +
              l.msg.substring(0, 120) +
              (l.msg.length > 120 ? "..." : "") +
              '</span><span class="bdg" style="background:' +
              l.col +
              "22;color:" +
              l.col +
              '"><i class="fas fa-' +
              (l.g === "male" ? "mars" : "venus") +
              '"></i></span></li>',
          )
          .join("");
      }
      function updAllLogs() {
        const tb = document.getElementById("allLogs");
        if (!tb) return;
        if (LOGS.length === 0) {
          tb.innerHTML =
            '<tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:28px">No call logs yet</td></tr>';
          return;
        }
        const tc = {
          emergency_code: "rgba(224,49,49,.1);color:#e03131",
          call_doctor: "rgba(26,86,219,.1);color:#1a56db",
          call_staff: "rgba(47,158,68,.1);color:#2f9e44",
          custom: "rgba(124,58,237,.1);color:#7c3aed",
        };
        tb.innerHTML = LOGS.map(
          (l) =>
            '<tr><td style="font-weight:700;color:var(--text-muted)">#' +
            l.id +
            '</td><td style="font-variant-numeric:tabular-nums;font-weight:600">' +
            l.time +
            '</td><td><span class="bdg" style="background:' +
            (tc[l.type] || "rgba(113,128,150,.1);color:#718096") +
            '">' +
            l.code +
            '</span></td><td style="font-size:.8rem;max-width:280px">' +
            l.msg.substring(0, 80) +
            (l.msg.length > 80 ? "..." : "") +
            "</td><td>" +
            l.loc +
            '</td><td><i class="fas fa-' +
            (l.g === "male" ? "mars" : "venus") +
            '" style="color:' +
            (l.g === "male" ? "#1a56db" : "#db2777") +
            '"></i> ' +
            l.g +
            '</td><td><span class="bdg" style="background:rgba(47,158,68,.1);color:#2f9e44">Sent</span></td></tr>',
        ).join("");
      }
      function clearLogs() {
        LOGS = [];
        ST = { t: 0, e: 0, d: 0, s: 0, c: 0 };
        updStats();
        updRC();
        updFeed();
        updAllLogs();
        toast("Session logs cleared", "info", "Logs");
      }

      // ===== STATS =====
      function updStats() {
        document.getElementById("stT").textContent = ST.t;
        document.getElementById("stE").textContent = ST.e;
        document.getElementById("stD").textContent = ST.d;
        document.getElementById("stS").textContent = ST.s;
      }

      // ===== ANALYTICS =====
      function updAnalytics() {
        const sg = document.getElementById("aSg");
        if (sg)
          sg.innerHTML =
            '<div class="sc"><div class="si bl"><i class="fas fa-broadcast-tower"></i></div><div class="sv"><div class="val">' +
            ST.t +
            '</div><div class="lbl">Total Calls</div></div></div><div class="sc"><div class="si re"><i class="fas fa-exclamation-circle"></i></div><div class="sv"><div class="val">' +
            ST.e +
            '</div><div class="lbl">Emergency</div></div></div><div class="sc"><div class="si gr"><i class="fas fa-user-md"></i></div><div class="sv"><div class="val">' +
            ST.d +
            '</div><div class="lbl">Doctor Pages</div></div></div><div class="sc"><div class="si pu"><i class="fas fa-users"></i></div><div class="sv"><div class="val">' +
            ST.s +
            '</div><div class="lbl">Staff Calls</div></div></div>';
        const at = document.getElementById("aType");
        if (at) {
          const tot = ST.t || 1;
          at.innerHTML = [
            { n: "Emergency Codes", v: ST.e, cl: "#e03131" },
            { n: "Doctor Pages", v: ST.d, cl: "#1a56db" },
            { n: "Staff Calls", v: ST.s, cl: "#2f9e44" },
            { n: "Custom", v: ST.c, cl: "#7c3aed" },
          ]
            .map(
              (x) =>
                '<div style="margin-bottom:14px"><div style="display:flex;justify-content:space-between;margin-bottom:4px"><span style="font-size:.83rem;font-weight:600">' +
                x.n +
                '</span><span style="font-size:.83rem;color:var(--text-muted)">' +
                x.v +
                '</span></div><div style="height:9px;background:var(--border);border-radius:100px;overflow:hidden"><div style="height:100%;width:' +
                Math.round((x.v / tot) * 100) +
                "%;background:" +
                x.cl +
                ';border-radius:100px;transition:width .6s"></div></div></div>',
            )
            .join("");
        }
        const al = document.getElementById("aLoc");
        if (al) {
          const lc = {};
          LOGS.forEach((l) => {
            lc[l.loc] = (lc[l.loc] || 0) + 1;
          });
          if (!Object.keys(lc).length) {
            al.innerHTML =
              '<p style="color:var(--text-muted);text-align:center;font-size:.83rem;padding:16px">No data yet</p>';
          } else {
            al.innerHTML = Object.entries(lc)
              .sort((a, b) => b[1] - a[1])
              .map(
                ([n, v]) =>
                  '<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)"><span style="font-size:.83rem">' +
                  n +
                  '</span><span class="bdg" style="background:rgba(26,86,219,.1);color:#1a56db">' +
                  v +
                  "</span></div>",
              )
              .join("");
          }
        }
      }

      // ===== VOICE TEST =====
      function testVoice(g) {
        speak(
          "Testing " +
            (g === "male" ? "male" : "female") +
            " voice. King Khalid Hospital, Hail. System operational.",
          g,
        );
        toast("Playing " + g + " voice test...", "info", "Voice Test");
      }

      // ===== TOAST =====
      function toast(msg, type, title) {
        const icons = {
          success: "fa-check",
          error: "fa-times",
          warning: "fa-exclamation",
          info: "fa-info",
        };
        const tc = document.getElementById("tc");
        const id = "t" + Date.now();
        const el = document.createElement("div");
        el.id = id;
        el.className = "ti " + (type || "info");
        el.innerHTML =
          '<div class="tic ' +
          (type || "info") +
          '"><i class="fas ' +
          (icons[type] || "fa-info") +
          '"></i></div><div class="tt"><div class="ttl">' +
          (title || "") +
          '</div><div class="tb2">' +
          msg +
          "</div></div><button onclick=\"document.getElementById('" +
          id +
          '\').remove()" style="background:none;border:none;cursor:pointer;color:var(--text-muted);padding:3px;margin-left:auto"><i class="fas fa-times"></i></button>';
        tc.appendChild(el);
        setTimeout(() => {
          const t = document.getElementById(id);
          if (t) t.remove();
        }, 4000);
      }
   