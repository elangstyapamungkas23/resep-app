import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'edit_profile_page.dart';
import 'detail_page.dart';
import 'riwayat_page.dart';
import 'edit_page.dart';
import '../services/api_service.dart';

class ProfilePage extends StatefulWidget {
  const ProfilePage({super.key});

  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  Map<String, dynamic>? profile;
  bool loading = true;

  @override
  void initState() {
    super.initState();
    getProfile();
  }

  Future<void> getProfile() async {
    try {
      final prefs = await SharedPreferences.getInstance();

      final userId = prefs.getInt('user_id');

      if (userId == null) {
        setState(() {
          loading = false;
        });
        return;
      }

      final response = await http.get(
        Uri.parse("http://192.168.18.55:8000/api/profile/$userId"),
      );

      final data = jsonDecode(response.body);

      print("PROFILE DATA:");
      print(data);

      setState(() {
        profile = data;
        loading = false;
      });
    } catch (e) {
      print("ERROR PROFILE:");
      print(e);

      setState(() {
        loading = false;
      });
    }
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();

    await prefs.clear();

    if (!mounted) return;

    Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
  }

  Future<void> hapusResep(int resepId) async {
    final response = await http.delete(
      Uri.parse("http://192.168.18.55:8000/api/reseps/$resepId"),
    );

    print(response.statusCode);
    print(response.body);

    if (response.statusCode == 200) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text("Resep berhasil dihapus")));

      getProfile();
    } else {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text("Gagal menghapus resep")));
    }
  }

  @override
  Widget build(BuildContext context) {
    if (loading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    if (profile == null || profile!['user'] == null) {
      return const Scaffold(body: Center(child: Text("Gagal memuat profil")));
    }

    final user = profile!['user'];

    print("FOTO => ${user['foto']}");
    print("COVER => ${user['cover']}");

    return Scaffold(
      backgroundColor: const Color(0xffF6F1EB),

      body: SingleChildScrollView(
        child: Column(
          children: [
            // COVER
            Container(
              height: 220,
              width: double.infinity,
              color: Colors.grey.shade300,
              child:
                  user['cover'] != null && user['cover'].toString().isNotEmpty
                  ? Image.network(
                      user['cover'].toString(),
                      fit: BoxFit.cover,
                      loadingBuilder: (context, child, progress) {
                        if (progress == null) return child;
                        return const Center(child: CircularProgressIndicator());
                      },
                      errorBuilder: (context, error, stackTrace) {
                        print("ERROR COVER: $error");
                        return const Center(child: Icon(Icons.image, size: 80));
                      },
                    )
                  : const Center(child: Icon(Icons.image, size: 80)),
            ),

            Transform.translate(
              offset: const Offset(0, -50),

              child: Container(
                margin: const EdgeInsets.symmetric(horizontal: 20),
                padding: const EdgeInsets.all(20),

                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(25),
                  boxShadow: const [
                    BoxShadow(blurRadius: 10, color: Colors.black12),
                  ],
                ),

                child: Column(
                  children: [
                    CircleAvatar(
                      radius: 55,
                      backgroundColor: Colors.orange.shade100,
                      child: ClipOval(
                        child:
                            user['foto'] != null &&
                                user['foto'].toString().isNotEmpty
                            ? Image.network(
                                user['foto'].toString(),
                                width: 110,
                                height: 110,
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) {
                                  print("ERROR FOTO: $error");
                                  return const Icon(Icons.person, size: 50);
                                },
                              )
                            : const Icon(Icons.person, size: 50),
                      ),
                    ),

                    const SizedBox(height: 15),

                    Text(
                      user['name'] ?? "",
                      style: const TextStyle(
                        fontSize: 26,
                        fontWeight: FontWeight.bold,
                      ),
                    ),

                    const SizedBox(height: 5),

                    Text(
                      user['email'] ?? "",
                      style: const TextStyle(color: Colors.grey),
                    ),

                    const SizedBox(height: 10),

                    Text(
                      user['bio'] ?? "Belum ada bio",
                      textAlign: TextAlign.center,
                    ),

                    const SizedBox(height: 20),

                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: [
                        statItem(profile!['total_resep'].toString(), "Resep"),

                        statItem(
                          profile!['total_favorit'].toString(),
                          "Favorit",
                        ),

                        statItem(
                          profile!['total_riwayat'].toString(),
                          "Riwayat",
                        ),
                      ],
                    ),

                    const SizedBox(height: 20),

                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.orange,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                        ),
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (_) => EditProfilePage(user: user),
                            ),
                          ).then((_) {
                            getProfile();
                          });
                        },
                        icon: const Icon(Icons.edit, color: Colors.white),
                        label: const Text(
                          "Edit Profil",
                          style: TextStyle(color: Colors.white),
                        ),
                      ),
                    ),

                    const SizedBox(height: 15),

                    SizedBox(
                      width: double.infinity,
                      height: 50,
                      child: ElevatedButton.icon(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.blue,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(30),
                          ),
                        ),
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(builder: (_) => RiwayatPage()),
                          );
                        },
                        icon: const Icon(Icons.history, color: Colors.white),
                        label: const Text(
                          "Riwayat",
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),

                    const SizedBox(height: 10),

                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.red,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                        ),
                        onPressed: logout,
                        icon: const Icon(Icons.logout, color: Colors.white),
                        label: const Text(
                          "Logout",
                          style: TextStyle(color: Colors.white),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 20),

              child: Align(
                alignment: Alignment.centerLeft,

                child: Text(
                  "Resep Saya 🍜",
                  style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                ),
              ),
            ),

            const SizedBox(height: 15),
            ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: profile!['reseps']?.length ?? 0,
              itemBuilder: (context, index) {
                final resep = profile!['reseps'][index];
                print("GAMBAR RESEP:");
                print(resep['gambar']);

                return Container(
                  margin: const EdgeInsets.symmetric(
                    horizontal: 20,
                    vertical: 10,
                  ),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(20),
                    boxShadow: const [
                      BoxShadow(
                        color: Colors.black12,
                        blurRadius: 8,
                        offset: Offset(0, 4),
                      ),
                    ],
                  ),

                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      ClipRRect(
                        borderRadius: const BorderRadius.vertical(
                          top: Radius.circular(20),
                        ),
                        child: resep['gambar'] != null
                            ? Image.network(
                                "http://192.168.18.55:8000/storage/${resep['gambar']}",
                                height: 180,
                                width: double.infinity,
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) {
                                  print("URL GAGAL:");
                                  print(
                                    "http://192.168.18.55:8000/storage/${resep['gambar']}",
                                  );
                                  print(error);

                                  return Container(
                                    height: 180,
                                    color: Colors.grey.shade300,
                                    child: const Icon(
                                      Icons.broken_image,
                                      size: 80,
                                    ),
                                  );
                                },
                              )
                            : Container(
                                height: 180,
                                color: Colors.grey.shade300,
                                child: const Icon(Icons.restaurant, size: 80),
                              ),
                      ),
                      Padding(
                        padding: const EdgeInsets.all(15),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              resep['nama_resep'] ?? '',
                              style: const TextStyle(
                                fontSize: 22,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              resep['deskripsi'] ?? '',
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                            ),
                            const SizedBox(height: 15),
                            Row(
                              children: [
                                Expanded(
                                  child: ElevatedButton(
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: Colors.orange,
                                    ),
                                    onPressed: () async {
                                      print("DATA RESEP PROFILE:");
                                      print(resep);
                                      final detailResep =
                                          await ApiService.getDetailResep(
                                            resep['id'],
                                          );

                                      Navigator.push(
                                        context,
                                        MaterialPageRoute(
                                          builder: (context) =>
                                              DetailPage(resep: detailResep),
                                        ),
                                      );
                                    },
                                    child: const Text(
                                      "Detail",
                                      style: TextStyle(color: Colors.white),
                                    ),
                                  ),
                                ),
                                const SizedBox(width: 10),
                                Expanded(
                                  child: ElevatedButton(
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: Colors.amber,
                                    ),
                                    onPressed: () {
                                      print(
                                        "DATA RESEP PROFILE YANG AKAN DIEDIT:",
                                      );
                                      print(resep);

                                      // Menggunakan MaterialPageRoute langsung tanpa perlu mendaftarkan route di main.dart
                                      Navigator.push(
                                        context,
                                        MaterialPageRoute(
                                          builder: (context) =>
                                              const EditPage(),
                                          // Mengirimkan data via RouteSettings arguments
                                          settings: RouteSettings(
                                            arguments: {
                                              'id': resep['id'],
                                              'nama_resep': resep['nama_resep'],
                                              'kategori_id':
                                                  resep['kategori_id'],
                                              'deskripsi': resep['deskripsi'],
                                              'bahan': resep['bahan'],
                                              'langkah': resep['langkah'],
                                              'gambar': resep['gambar'] != null
                                                  ? "http://192.168.18.55:8000/storage/${resep['gambar']}"
                                                  : null,
                                            },
                                          ),
                                        ),
                                      ).then((_) {
                                        // Otomatis menyegarkan data halaman profil setelah selesai mengedit
                                        getProfile();
                                      });
                                    },
                                    child: const Text(
                                      "Edit",
                                      style: TextStyle(color: Colors.white),
                                    ),
                                  ),
                                ),
                                const SizedBox(width: 10),
                                Expanded(
                                  child: ElevatedButton(
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: Colors.red,
                                    ),
                                    onPressed: () async {
                                      bool? konfirmasi = await showDialog(
                                        context: context,
                                        builder: (context) => AlertDialog(
                                          title: const Text("Hapus Resep"),
                                          content: const Text(
                                            "Yakin ingin menghapus resep ini?",
                                          ),
                                          actions: [
                                            TextButton(
                                              onPressed: () {
                                                Navigator.pop(context, false);
                                              },
                                              child: const Text("Batal"),
                                            ),
                                            TextButton(
                                              onPressed: () {
                                                Navigator.pop(context, true);
                                              },
                                              child: const Text(
                                                "Hapus",
                                                style: TextStyle(
                                                  color: Colors.red,
                                                ),
                                              ),
                                            ),
                                          ],
                                        ),
                                      );

                                      if (konfirmasi == true) {
                                        hapusResep(resep['id']);
                                      }
                                    },
                                    child: const Text(
                                      "Hapus",
                                      style: TextStyle(color: Colors.white),
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                );
              },
            ),

            const SizedBox(height: 30),
          ],
        ),
      ),
    );
  }

  Widget statItem(String value, String title) {
    return Column(
      children: [
        Text(
          value,
          style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
        Text(title),
      ],
    );
  }
}
