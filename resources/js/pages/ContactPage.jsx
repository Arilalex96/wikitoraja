import React from "react";

const ContactPage = () => {
    return (
        <div className="container mt-5">
            <div className="row">
                <div className="col-lg-8">
                    <h1 className="mb-4">
                        Hubungi Kami - Terhubung dengan WikiToraja
                    </h1>
                    <div className="card mb-4">
                        <div className="card-body">
                            <h2 className="card-title mb-4">
                                Kirim Pesan Kepada Kami
                            </h2>
                            <form onSubmit={(e) => e.preventDefault()}>
                                <div className="mb-3">
                                    <label htmlFor="name" className="form-label">
                                        Nama Lengkap
                                    </label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        id="name"
                                        placeholder="Masukkan nama lengkap Anda"
                                    />
                                </div>
                                <div className="mb-3">
                                    <label htmlFor="email" className="form-label">
                                        Alamat Email
                                    </label>
                                    <input
                                        type="email"
                                        className="form-control"
                                        id="email"
                                        placeholder="Masukkan alamat email Anda"
                                    />
                                </div>
                                <div className="mb-3">
                                    <label htmlFor="subject" className="form-label">
                                        Subjek
                                    </label>
                                    <select className="form-select" id="subject">
                                        <option>
                                            Informasi tentang Budaya Toraja
                                        </option>
                                        <option>
                                            Kolaborasi dalam Pelestarian Budaya
                                        </option>
                                        <option>Kontribusi Konten</option>
                                        <option>Pertanyaan Umum</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>
                                <div className="mb-3">
                                    <label htmlFor="message" className="form-label">
                                        Pesan
                                    </label>
                                    <textarea
                                        className="form-control"
                                        id="message"
                                        rows="5"
                                        placeholder="Tulis pesan Anda di sini"
                                    ></textarea>
                                </div>
                                <button
                                    type="button"
                                    className="btn btn-primary"
                                >
                                    Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div className="col-lg-4">
                    <div className="card mb-4">
                        <div className="card-header">Lokasi Kami</div>
                        <div className="card-body">
                            <p>
                                <strong>Lokasi WikiToraja</strong>
                            </p>
                            <p>
                                Jl. Poros Makale-Rantepao, Tana Toraja
                                <br />
                                Tana Toraja, Sulawesi Selatan
                                <br />
                                Indonesia
                            </p>
                            <p>
                                <strong>Jam Operasional Kami:</strong>
                                <br />
                                Senin - Jumat: 08:00 - 17:00 WITA
                                <br />
                                Sabtu: 09:00 - 15:00 WITA
                            </p>
                        </div>
                    </div>
                    <div className="card mb-4">
                        <div className="card-header">Kontak Langsung</div>
                        <div className="card-body">
                            <ul className="list-unstyled mb-0">
                                <li className="mb-2">
                                    📞 Telepon:{" "}
                                    <a href="tel:+6242312345">+62 423 12345</a>
                                </li>
                                <li className="mb-2">
                                    📱 WhatsApp: +62 812 3456 7890
                                </li>
                                <li className="mb-2">
                                    ✉️ Email:{" "}
                                    <a href="mailto:info@wikitoraja.com">
                                        info@wikitoraja.com
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div className="card mb-4">
                        <div className="card-header">Media Sosial</div>
                        <div className="card-body">
                            <ul className="list-unstyled mb-0">
                                <li className="mb-2">
                                    📸 Instagram:{" "}
                                    <a
                                        href="https://instagram.com/wikitoraja"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        @wikitoraja
                                    </a>
                                </li>
                                <li className="mb-2">
                                    🔵 Facebook:{" "}
                                    <a
                                        href="https://facebook.com/wikitoraja"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        WikiToraja Official
                                    </a>
                                </li>
                                <li className="mb-2">
                                    🐦 Twitter:{" "}
                                    <a
                                        href="https://twitter.com/WikiToraja"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        @WikiToraja
                                    </a>
                                </li>
                                <li className="mb-2">
                                    ▶️ YouTube:{" "}
                                    <a
                                        href="https://youtube.com/wikitoraja"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        WikiToraja Channel
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ContactPage;
