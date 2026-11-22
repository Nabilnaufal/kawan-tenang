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

export async function pindaiWajah(video) {
    if (! stream?.active || video.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) {
        throw new Error('Preview kamera belum siap. Tutup kamera lalu buka lagi.');
    }

    const faceapi = await muatModel();

    const hasil = await faceapi
        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.4 }))
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (! hasil) {
        throw new Error('Wajah belum terdeteksi. Hadapkan wajah ke kamera dan coba lagi.');
    }

    return Array.from(hasil.descriptor);
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
