class Resep {
  final int id;
  final String namaResep;
  final String deskripsi;
  final String gambar;

  Resep({
    required this.id,
    required this.namaResep,
    required this.deskripsi,
    required this.gambar,
  });

  factory Resep.fromJson(Map<String, dynamic> json) {
    return Resep(
      id: json['id'],
      namaResep: json['nama_resep'],
      deskripsi: json['deskripsi'],
      gambar: json['gambar'] ?? '',
    );
  }
}
