import React from "react";

const AboutPage = () => {
    return (
        <div className="container mt-5">
            <div className="row">
                <div className="col-lg-8">
                    <h1 className="mb-4">Tentang WikiToraja</h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <h2 className="card-title">
                                Warisan Budaya Toraja
                            </h2>
                            <p className="card-text">
                                Toraja adalah kelompok etnis yang tinggal di
                                pegunungan utara Sulawesi Selatan, Indonesia.
                                Wilayah ini dikenal dengan budaya dan tradisi unik
                                yang telah dilestarikan hingga saat ini.
                            </p>
                            <h3>Ciri-ciri Toraja:</h3>
                            <ul>
                                <li>
                                    <strong>Tongkonan</strong> - Rumah adat Toraja
                                    dengan atap melengkung menyerupai perahu,
                                    melambangkan identitas dan status sosial.
                                </li>
                                <li>
                                    <strong>Upacara Rambu Solo</strong> - Upacara
                                    pemakaman yang merupakan ritual penting dalam
                                    budaya Toraja.
                                </li>
                                <li>
                                    <strong>Ukiran Toraja (Pa'ssura)</strong>{" "}
                                    - Seni ukir tradisional dengan motif geometris
                                    yang memiliki makna filosofis.
                                </li>
                                <li>
                                    <strong>Penguburan di Tebing</strong> - Makam
                                    yang dipahat di tebing batu, menunjukkan
                                    keunikan tradisi pemakaman Toraja.
                                </li>
                            </ul>
                            <h3>Nilai-nilai Budaya:</h3>
                            <p>Orang Toraja menjunjung nilai-nilai seperti:</p>
                            <ul>
                                <li>Keluarga dan gotong royong</li>
                                <li>Hormat kepada leluhur</li>
                                <li>Keseimbangan dengan alam</li>
                                <li>Pelestarian tradisi dan adat istiadat</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div className="col-lg-4">
                    <div className="card mb-4">
                        <div className="card-header">Visi WikiToraja</div>
                        <div className="card-body">
                            <p>
                                Menjadi sumber informasi terpercaya tentang
                                budaya dan tradisi Toraja, serta berperan dalam
                                pelestarian warisan budaya untuk generasi
                                mendatang.
                            </p>
                        </div>
                    </div>
                    <div className="card mb-4">
                        <div className="card-header">Misi WikiToraja</div>
                        <div className="card-body">
                            <ul className="list-unstyled mb-0">
                                <li>
                                    ✓ Mendokumentasikan kekayaan budaya Toraja
                                </li>
                                <li>
                                    ✓ Menyebarkan pengetahuan tentang tradisi Toraja
                                </li>
                                <li>
                                    ✓ Menghubungkan komunitas pecinta budaya
                                </li>
                                <li>
                                    ✓ Mendukung pelestarian warisan budaya
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AboutPage;
