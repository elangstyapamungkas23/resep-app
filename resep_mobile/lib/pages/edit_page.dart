import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

class EditPage extends StatefulWidget {
  const EditPage({super.key});

  @override
  State<EditPage> createState() => _EditPageState();
}

class _EditPageState extends State<EditPage> {
  final _formKey = GlobalKey<FormState>();

  final TextEditingController namaController = TextEditingController();
  final TextEditingController deskripsiController = TextEditingController();
  final TextEditingController bahanController = TextEditingController();
  final TextEditingController langkahController = TextEditingController();

  File? selectedImage;
  String? selectedKategori;
  String? resepId;
  String? existingImageUrl; // Menyimpan URL gambar lama dari server
  bool isDataLoaded = false;

  final List<String> kategoriList = [
    "Makanan",
    "Minuman",
    "Dessert",
    "Healthy",
  ];

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

  String getKategoriNama(String id) {
    switch (id) {
      case "1":
        return "Makanan";
      case "2":
        return "Minuman";
      case "3":
        return "Dessert";
      case "4":
        return "Healthy";
      default:
        return "Makanan";
    }
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();

    // Otomatis menangkap data resep lama yang dikirim dari profile_page
    if (!isDataLoaded) {
      final Map<String, dynamic>? arguments =
          ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;

      if (arguments != null) {
        resepId = arguments['id']?.toString();
        namaController.text = arguments['nama_resep'] ?? '';
        deskripsiController.text = arguments['deskripsi'] ?? '';
        bahanController.text = arguments['bahan'] ?? '';
        langkahController.text = arguments['langkah'] ?? '';

        String kategoriId = arguments['kategori_id']?.toString() ?? '1';
        selectedKategori = getKategoriNama(kategoriId);
        existingImageUrl = arguments['gambar'];
      }
      isDataLoaded = true;
    }
  }

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

  // 🔥 FUNGSI UPDATE DATA KE API LARAVEL
  Future<void> updateResep() async {
    if (!_formKey.currentState!.validate()) return;
    if (resepId == null) return;

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('token');

      if (token == null) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(const SnackBar(content: Text("Silakan login ulang")));
        return;
      }

      // Menggunakan POST dengan menyertakan field _method = PUT agar dibaca multipart oleh Laravel
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('http://192.168.18.55:8000/api/reseps/$resepId'),
      );

      request.headers['Authorization'] = 'Bearer $token';
      request.fields['_method'] =
          'PUT'; // Metode spoofing agar aman kirim file gambar
      request.fields['nama_resep'] = namaController.text;
      request.fields['kategori_id'] = getKategoriId();
      request.fields['deskripsi'] = deskripsiController.text;
      request.fields['bahan'] = bahanController.text;
      request.fields['langkah'] = langkahController.text;

      // Hanya masukkan gambar baru ke request jika user memilih gambar baru
      if (selectedImage != null) {
        request.files.add(
          await http.MultipartFile.fromPath('gambar', selectedImage!.path),
        );
      }

      final response = await request.send();
      final responseBody = await response.stream.bytesToString();

      print(response.statusCode);
      print(responseBody);

      if (response.statusCode == 200 || response.statusCode == 201) {
        if (!mounted) return;

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Resep berhasil diperbarui")),
        );

        // ✅ PERBAIKAN DI SINI: Langsung tutup halaman edit untuk kembali ke profile
        Navigator.pop(context);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Gagal memperbarui (${response.statusCode})")),
        );
      }
    } catch (e, s) {
      print("ERROR:");
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
                          // ✅ PERBAIKAN DI SINI: Tombol kembali panah atas pakai Navigator.pop
                          Navigator.pop(context);
                        },
                        icon: const Icon(Icons.arrow_back_ios),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  const Text(
                    "✏️ Edit Resep",
                    style: TextStyle(fontSize: 32, fontWeight: FontWeight.w900),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    "Perbarui resep terbaikmu agar tetap menarik bagi pengguna lain.",
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
                      child: selectedImage != null
                          ? ClipRRect(
                              borderRadius: BorderRadius.circular(25),
                              child: Image.file(
                                selectedImage!,
                                fit: BoxFit.cover,
                              ),
                            )
                          : (existingImageUrl != null &&
                                existingImageUrl!.isNotEmpty)
                          ? ClipRRect(
                              borderRadius: BorderRadius.circular(25),
                              child: Image.network(
                                existingImageUrl!,
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) {
                                  return const Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      Icon(
                                        Icons.broken_image_outlined,
                                        size: 60,
                                      ),
                                      SizedBox(height: 10),
                                      Text("Gagal memuat gambar lama"),
                                    ],
                                  );
                                },
                              ),
                            )
                          : const Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.image_outlined, size: 60),
                                SizedBox(height: 10),
                                Text("Pilih Foto Resep"),
                              ],
                            ),
                    ),
                  ),
                  const SizedBox(height: 30),

                  SizedBox(
                    width: double.infinity,
                    height: 58,
                    child: ElevatedButton(
                      onPressed: updateResep,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.orange,
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(50),
                        ),
                      ),
                      child: const Text(
                        "🔄 Update Resep",
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
                        // ✅ PERBAIKAN DI SINI: Tombol Batal diganti Navigator.pop agar kembali dengan aman
                        Navigator.pop(context);
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
