let realtimeRunning = false;
let lastRealtimeHash = "";

async function realtime() {

    if (realtimeRunning) return;

    realtimeRunning = true;

    try {

        let url = "/realtime";

        if (window.location.pathname.includes("/admin/riwayat")) {
            url = "/admin/riwayat/realtime";
        }

        const response = await fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            },
            cache: "no-store"
        });

        if (!response.ok) {
            realtimeRunning = false;
            return;
        }

        const data = await response.json();

        const hash = JSON.stringify(data);

        if (hash !== lastRealtimeHash) {
            lastRealtimeHash = hash;
            console.log("REALTIME EVENT", data.monitoring_logs.length);

document.dispatchEvent(
    new CustomEvent("realtime-update", {
        detail: data
    })
);
        }

    } catch (e) {
        console.error(e);
    }

    realtimeRunning = false;
}

// ===== LISTENER UTAMA =====
document.addEventListener("realtime-update", function (e) {

    const d = e.detail;

    // ===== INFO SISTEM =====
    const infoUpdate = document.getElementById("rt-info-update");
    if (infoUpdate) infoUpdate.innerHTML = d.server_time;

    // ===== SUMMARY CARDS =====
    const cardMap = {
        "Total Data"     : d.total_data,
        "Device Online"  : d.device_online,
        "Device Offline" : d.total_device - d.device_online,
        "Data Hari Ini"  : d.data_hari_ini,
        "Total Kelompok" : d.total_kelompok,
        "Total Aktuator" : d.total_aktuator,
        "Total Sensor"   : d.total_sensor,
    };

    document.querySelectorAll(".rt-card-value").forEach(function (el) {
        const label = el.dataset.label;
        if (label && cardMap[label] !== undefined) {
            el.innerHTML = cardMap[label];
        }
    });

    // ===== NODE TABLE =====
    if (d.devices) {
        d.devices.forEach(function (device) {
            const row = document.getElementById("rt-node-row-" + device.id);
            if (!row) return;

            const badge = row.querySelector(".rt-node-status");
            if (badge) {
                badge.className = "rt-node-status inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold "
                    + (device.online ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700");
                badge.innerHTML = device.online ? "● Online" : "● Offline";
            }

            const lastUpdate = row.querySelector(".rt-node-last-update");
            if (lastUpdate && device.sensor.length > 0) {
                lastUpdate.innerHTML = device.sensor[0].updated_at;
            }
        });
    }

    // ===== QoS SUMMARY =====
    const q = d.qos;
    if (q) {
        const rtUpdate = document.getElementById("rt-qos-update");
        if (rtUpdate) rtUpdate.innerHTML = "Update terakhir: " + d.server_time;

        const throughput = document.getElementById("rt-throughput");
        if (throughput) throughput.innerHTML = Number(q.throughput).toFixed(2) + " bps";

        const delay = document.getElementById("rt-delay");
        if (delay) delay.innerHTML = Number(q.delay).toFixed(2) + " ms";

        const jitter = document.getElementById("rt-jitter");
        if (jitter) jitter.innerHTML = Number(q.jitter).toFixed(2) + " ms";

        const loss = document.getElementById("rt-loss");
        if (loss) loss.innerHTML = Number(q.packet_loss).toFixed(2) + " %";

        const tBar = document.getElementById("rt-throughput-bar");
        if (tBar) tBar.style.width = Math.min(q.throughput, 100) + "%";

        const dBar = document.getElementById("rt-delay-bar");
        if (dBar) dBar.style.width = Math.min(q.delay, 100) + "%";

        const jBar = document.getElementById("rt-jitter-bar");
        if (jBar) jBar.style.width = Math.min(q.jitter * 2, 100) + "%";

        const lBar = document.getElementById("rt-loss-bar");
        if (lBar) lBar.style.width = Math.min(q.packet_loss, 100) + "%";
    }

    // ===== DEVICE MONITORING - STATUS IOT =====
    if (d.devices) {
        d.devices.forEach(function (device) {

            // update sensor value di status IoT
            device.sensor.forEach(function (sensor) {
                const el = document.getElementById("rt-sensor-" + sensor.id);
                if (el) el.innerHTML = sensor.value + " " + sensor.satuan;
            });

            // update actuator status
            device.actuator.forEach(function (act) {
                const el = document.getElementById("rt-actuator-" + act.id);
                if (el) el.innerHTML = act.status ? "ON" : "OFF";
            });

            // update chart
            const chart = window["deviceChart_" + device.id];
            if (chart && device.sensor.length > 0) {
                const now = new Date().toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit", second: "2-digit" });
                chart.data.labels.push(now);
                chart.data.datasets[0].data.push(Number(device.sensor[0].value));
                if (chart.data.labels.length > 10) {
                    chart.data.labels.shift();
                    chart.data.datasets[0].data.shift();
                }
                chart.update("none");
            }
        });
    }

});

window.addEventListener("load", function () {
    realtime();
    setInterval(realtime, 5000);   // dari 2000 → 5000
});

window.addEventListener("focus", realtime);

document.addEventListener("visibilitychange", function () {
    if (!document.hidden) realtime();
});