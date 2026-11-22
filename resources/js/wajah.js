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

    stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
        audio: false,
    });

    video.srcObject = stream;
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
