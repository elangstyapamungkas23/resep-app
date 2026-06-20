import 'dart:io';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

class CreatePage extends StatefulWidget {
  const CreatePage({super.key});

  @override
  State<CreatePage> createState() => _CreatePageState();
}

class _CreatePageState extends State<CreatePage> {
  final _formKey = GlobalKey<FormState>();

  final TextEditingController namaController = TextEditingController();
  final TextEditingController deskripsiController = TextEditingController();
  final TextEditingController bahanController = TextEditingController();
  final TextEditingController langkahController = TextEditingController();

  File? selectedImage;
  String? selectedKategori;

  String getKategoriId() {
    switch (selectedKategori) {
      case "Makanan":
        return "1";
      case "Minuman":
        return "2";
      case "Dessert":
        return "3";
      case "Healthy":
        return "4";
      default:
        return "1";
    }
  }

  final List<String> kategoriList = [
    "Makanan",
    "Minuman",
    "Dessert",
    "Healthy",
  ];

  Future<void> pickImage() async {
    final picker = ImagePicker();
    final image = await picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 80,
    );

    if (image != null) {
      setState(() {
        selectedImage = File(image.path);
      });
    }
  }

  InputDecoration inputDecoration(String hint) {
    return InputDecoration(
      hintText: hint,
      filled: true,
      fillColor: Colors.white,
      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(20),
        borderSide: BorderSide(color: Colors.grey.shade300),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(20),
        borderSide: BorderSide(color: Colors.grey.shade300),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(20),
        borderSide: const BorderSide(color: Colors.orange, width: 2),
      ),
    );
  }

  Future<void> simpanResep() async {
    if (!_formKey.currentState!.validate()) return;

    try {
      final prefs = await SharedPreferences.getInstance();
      final userId = prefs.getInt('user_id');

      print("DEBUG PRO - MENGIRIM DATA RESEP UTK USER_ID: $userId");

      final request = http.MultipartRequest(
        'POST',
        Uri.parse('http://192.168.18.55:8000/api/reseps'),
      );

      // ✅ KUNCI AMAN: HANYA kirimkan header Accept JSON agar Laravel tidak meremove request.
      // Kita SAMA SEKALI tidak menyentuh atau mengirim header 'Authorization' agar Sanctum tidak mencegat rute publik ini.
      request.headers.clear();
      request.headers['Accept'] = 'application/json';

      // Pasang field form data resep secara lengkap
      if (userId != null) {
        request.fields['user_id'] = userId.toString();
      }
      request.fields['nama_resep'] = namaController.text;
      request.fields['kategori_id'] = getKategoriId();
      request.fields['deskripsi'] = deskripsiController.text;
      request.fields['bahan'] = bahanController.text;
      request.fields['langkah'] = langkahController.text;

      if (selectedImage != null) {
        request.files.add(
          await http.MultipartFile.fromPath('gambar', selectedImage!.path),
        );
      }

      final response = await request.send();
      final responseBody = await response.stream.bytesToString();

      print("STATUS CODE TERBARU: ${response.statusCode}");
      print("RESPONSE BODY TERBARU: $responseBody");

      final Map<String, dynamic> decodedData = jsonDecode(responseBody);

      if (response.statusCode == 200 || response.statusCode == 201) {
        if (!mounted) return;

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Resep berhasil disimpan")),
        );

        // ✅ Kembali ke Home utama agar navigasi stack dibersihkan dan halaman depan merefresh data dari nol
        Navigator.pushNamedAndRemoveUntil(context, '/home', (route) => false);
      } else {
        if (!mounted) return;
        String pesanError = decodedData['message'] ?? "Terjadi kesalahan";
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              "Gagal menyimpan (${response.statusCode}): $pesanError",
            ),
          ),
        );
      }
    } catch (e, s) {
      print("ERROR SEWAKTU PROSES SIMPAN:");
      print(e);
      print(s);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF6F1EB),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(40),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 15,
                  offset: const Offset(0, 5),
                ),
              ],
            ),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      IconButton(
                        onPressed: () {
                          Navigator.pushReplacementNamed(context, '/home');
                        },
                        icon: const Icon(Icons.arrow_back_ios),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  const Text(
                    "➕ Tambah Resep Baru",
                    style: TextStyle(fontSize: 32, fontWeight: FontWeight.w900),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    "Bagikan resep terbaikmu ke seluruh pengguna Recipe App.",
                    style: TextStyle(color: Colors.grey.shade600, fontSize: 15),
                  ),
                  const SizedBox(height: 30),
                  const Text(
                    "Nama Resep",
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: namaController,
                    decoration: inputDecoration("Contoh: Nasi Goreng Spesial"),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return "Nama resep wajib diisi";
                      }
                      return null;
                    },
                  ),
                  const SizedBox(height: 20),
                  const Text(
                    "Kategori",
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  DropdownButtonFormField<String>(
                    value: selectedKategori,
                    decoration: inputDecoration("Pilih Kategori"),
                    items: kategoriList.map((kategori) {
                      return DropdownMenuItem(
                        value: kategori,
                        child: Text(kategori),
                      );
                    }).toList(),
                    onChanged: (value) {
                      setState(() {
                        selectedKategori = value;
                      });
                    },
                  ),
                  const SizedBox(height: 20),
                  const Text(
                    "Deskripsi",
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: deskripsiController,
                    maxLines: 4,
                    decoration: inputDecoration("Jelaskan resepmu..."),
                  ),
                  const SizedBox(height: 20),
                  const Text(
                    "Bahan-bahan",
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: bahanController,
                    maxLines: 6,
                    decoration: inputDecoration("Masukkan daftar bahan..."),
                  ),
                  const SizedBox(height: 20),
                  const Text(
                    "Langkah Memasak",
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  TextFormField(
                    controller: langkahController,
                    maxLines: 8,
                    decoration: inputDecoration("Tuliskan langkah memasak..."),
                  ),
                  const SizedBox(height: 20),
                  const Text(
                    "Foto Resep",
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 12),
                  GestureDetector(
                    onTap: pickImage,
                    child: Container(
                      height: 230,
                      width: double.infinity,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(25),
                        border: Border.all(color: Colors.grey.shade300),
                      ),
                      child: selectedImage == null
                          ? const Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.image_outlined, size: 60),
                                SizedBox(height: 10),
                                Text("Pilih Foto Resep"),
                              ],
                            )
                          : ClipRRect(
                              borderRadius: BorderRadius.circular(25),
                              child: Image.file(
                                selectedImage!,
                                fit: BoxFit.cover,
                              ),
                            ),
                    ),
                  ),
                  const SizedBox(height: 30),
                  SizedBox(
                    width: double.infinity,
                    height: 58,
                    child: ElevatedButton(
                      onPressed: simpanResep,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.orange,
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(50),
                        ),
                      ),
                      child: const Text(
                        "🍜 Simpan Resep",
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    height: 58,
                    child: OutlinedButton(
                      onPressed: () {
                        Navigator.pushReplacementNamed(context, '/home');
                      },
                      style: OutlinedButton.styleFrom(
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(50),
                        ),
                      ),
                      child: const Text(
                        "Batal",
                        style: TextStyle(fontWeight: FontWeight.bold),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
