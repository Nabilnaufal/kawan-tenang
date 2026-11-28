let siap = null;
let stream = null;

/** Muat model deteksi + landmark + pengenal wajah sekali saja (lazy, chunk terpisah). */
function muatModel() {
    siap ??= (async () => {
        const faceapi = await import('@vladmandic/face-api');

        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
        ]);

        return faceapi;
    })();

    return siap;
}

export async function mulaiKamera(video) {
    if (! video) {
        throw new Error('Elemen preview kamera belum siap. Tutup dialog lalu buka kembali.');
    }

    if (! navigator.mediaDevices?.getUserMedia) {
        throw new Error('Browser ini tidak mendukung akses kamera.');
    }

    hentikanKamera(video);

    stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: { ideal: 'user' }, width: { ideal: 640 }, height: { ideal: 480 } },
        audio: false,
    });

    video.srcObject = stream;

    await new Promise((resolve, reject) => {
        const timeout = window.setTimeout(() => reject(new Error('Kamera terbuka, tetapi video tidak mengirim gambar. Coba refresh halaman.')), 5000);
        video.onloadedmetadata = () => {
            window.clearTimeout(timeout);
            resolve();
        };
        video.onerror = () => {
            window.clearTimeout(timeout);
            reject(new Error('Preview kamera gagal ditampilkan oleh browser.'));
        };
    });

    await video.play();
    muatModel().catch(() => {});
}

export function hentikanKamera(video) {
    stream?.getTracks().forEach((track) => track.stop());
    stream = null;

    if (video) {
        video.srcObject = null;
    }
}

function jarak(a, b) {
    return Math.hypot(a.x - b.x, a.y - b.y);
}

/** Ratio of inner-mouth height to width; a higher ratio means the mouth is open. */
function rasioMulutTerbuka(landmarks) {
    const posisi = landmarks?.positions ?? [];

    if (posisi.length < 68) {
        return 0;
    }

    const lebar = jarak(posisi[60], posisi[64]);

    return lebar === 0 ? 0 : jarak(posisi[62], posisi[66]) / lebar;
}

function tunggu(ms) {
    return new Promise((resolve) => window.setTimeout(resolve, ms));
}

export async function pindaiWajah(video, { onStatus = () => {}, timeoutMs = 12000 } = {}) {
    if (! stream?.active || video.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) {
        throw new Error('Preview kamera belum siap. Tutup kamera lalu buka lagi.');
    }

    const faceapi = await muatModel();
    const mulai = Date.now();
    let mulutTerbukaBerurutan = 0;

    onStatus('Hadapkan wajah ke kamera.');

    while (Date.now() - mulai < timeoutMs) {
        if (! stream?.active) {
            throw new Error('Kamera ditutup sebelum pemeriksaan selesai.');
        }

        const hasil = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.4 }))
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (! hasil) {
            mulutTerbukaBerurutan = 0;
            onStatus('Wajah belum terdeteksi. Hadapkan wajah ke kamera.');
            await tunggu(120);
            continue;
        }

        if (rasioMulutTerbuka(hasil.landmarks) >= 0.38) {
            mulutTerbukaBerurutan++;
            onStatus('Mulut terdeteksi terbuka. Tahan sebentar…');

            if (mulutTerbukaBerurutan >= 2) {
                return Array.from(hasil.descriptor);
            }
        } else {
            mulutTerbukaBerurutan = 0;
            onStatus('Buka mulut saat diminta untuk memeriksa liveness.');
        }

        await tunggu(120);
    }

    throw new Error('Liveness gagal. Pastikan wajah terlihat jelas lalu buka mulut saat diminta.');
}

export function pesanKamera(error) {
    const nama = error?.name;

    if (nama === 'NotAllowedError' || nama === 'PermissionDeniedError') {
        return 'Akses kamera ditolak. Klik ikon kamera di kiri alamat 127.0.0.1:8000, pilih Izinkan, lalu buka kamera lagi.';
    }

    if (nama === 'NotFoundError' || nama === 'DevicesNotFoundError') {
        return 'Kamera tidak ditemukan. Pastikan webcam terpasang dan tidak sedang dipakai aplikasi lain.';
    }

    if (nama === 'NotReadableError' || nama === 'TrackStartError') {
        return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi seperti Zoom/Meet/OBS lalu coba lagi.';
    }

    if (nama === 'SecurityError') {
        return 'Browser memblokir kamera pada halaman ini. Buka melalui http://127.0.0.1:8000 atau localhost.';
    }

    return error?.message || 'Kamera tidak bisa dibuka. Periksa izin kamera lalu coba lagi.';
}
