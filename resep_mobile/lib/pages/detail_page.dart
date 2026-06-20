import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import '../services/api_service.dart';
import 'dart:convert';

class DetailPage extends StatefulWidget {
  final dynamic
  resep; // Menggunakan dynamic agar fleksibel menerima Map atau List

  const DetailPage({super.key, required this.resep});

  @override
  State<DetailPage> createState() => _DetailPageState();
}

class _DetailPageState extends State<DetailPage> {
  int? currentUserId;
  String? currentUserName;

  bool isFavorite = false;
  int? favoritId;

  int userRating = 0;
  int? ratingId;

  Map resep = {};

  Future toggleFavorit() async {
    print("IS FAVORITE : $isFavorite");

    if (isFavorite) {
      await hapusFavorit();
    } else {
      await tambahFavorit();
    }
  }

  Future tambahFavorit() async {
    final response = await http.post(
      Uri.parse("http://192.168.18.55:8000/api/favorits"),
      body: {
        "user_id": currentUserId.toString(),
        "resep_id": resep['id'].toString(),
      },
    );

    if (response.statusCode == 200 || response.statusCode == 201) {
      final data = jsonDecode(response.body);

      setState(() {
        isFavorite = true;
        favoritId = data['data']['id'];
      });

      print("isFavorite = $isFavorite");
      print("favoritId = $favoritId");
    }
  }

  Future hapusFavorit() async {
    final response = await http.delete(
      Uri.parse("http://192.168.18.55:8000/api/favorits/$favoritId"),
    );

    if (response.statusCode == 200) {
      setState(() {
        isFavorite = false;
        favoritId = null;
      });

      print("isFavorite setelah hapus = $isFavorite");
    }
  }

  Future cekFavorit() async {
    print("CEK FAVORIT");
    print("USER : $currentUserId");
    print("RESEP : ${resep['id']}");

    final response = await http.get(
      Uri.parse("http://192.168.18.55:8000/api/favorits"),
    );

    final data = jsonDecode(response.body);

    bool ditemukan = false;

    for (var item in data['data']) {
      if (item['user_id'] == currentUserId && item['resep_id'] == resep['id']) {
        ditemukan = true;

        if (!mounted) return;

        setState(() {
          isFavorite = true;
          favoritId = item['id'];
        });

        break;
      }
    }

    if (!ditemukan) {
      if (!mounted) return;

      setState(() {
        isFavorite = false;
        favoritId = null;
      });
    }
  }

  Future cekRating() async {
    final response = await http.get(
      Uri.parse("http://192.168.18.55:8000/api/ratings"),
    );

    final data = jsonDecode(response.body);

    for (var item in data['data']) {
      if (item['user_id'] == currentUserId && item['resep_id'] == resep['id']) {
        if (!mounted) return;

        setState(() {
          userRating = item['rating'];
          ratingId = item['id'];
        });
        print("RATING USER : $userRating");
        print("RATING ID : $ratingId");
        break;
      }
    }
  }

  Future kirimRating(int rating) async {
    final response = await http.post(
      Uri.parse("http://192.168.18.55:8000/api/ratings"),
      body: {
        "user_id": currentUserId.toString(),
        "resep_id": resep['id'].toString(),
        "rating": rating.toString(),
      },
    );

    print(response.body);

    if (response.statusCode == 200 || response.statusCode == 201) {
      final data = jsonDecode(response.body);

      setState(() {
        userRating = rating;
        ratingId = data['data']['id'];
      });

      await loadDetailResep();
    }
  }

  Future<void> hapusRating() async {
    print("TOMBOL HAPUS DIKLIK");
    print("ratingId = $ratingId");

    final url = "http://192.168.18.55:8000/api/ratings/$ratingId";

    final response = await http.delete(Uri.parse(url));

    if (response.statusCode == 200) {
      setState(() {
        userRating = 0;
        ratingId = null;
      });

      await loadDetailResep();
    }
  }

  final TextEditingController komentarController = TextEditingController();

  Future kirimKomentar() async {
    if (komentarController.text.trim().isEmpty) return;

    final response = await http.post(
      Uri.parse("http://192.168.18.55:8000/api/komentars"),
      body: {
        "user_id": currentUserId.toString(),
        "resep_id": resep['id'].toString(),
        "komentar": komentarController.text.trim(),
      },
    );

    if (response.statusCode == 200 || response.statusCode == 201) {
      await loadDetailResep();
      komentarController.clear();

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Komentar berhasil dikirim")),
      );
    }
  }

  Future<void> editKomentar(int komentarId, String komentarLama) async {
    TextEditingController controller = TextEditingController(
      text: komentarLama,
    );

    showDialog(
      context: context,
      builder: (_) {
        return AlertDialog(
          title: const Text("Edit Komentar"),
          content: TextField(controller: controller, maxLines: 3),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: const Text("Batal"),
            ),
            ElevatedButton(
              onPressed: () async {
                final response = await http.put(
                  Uri.parse(
                    "http://192.168.18.55:8000/api/komentars/$komentarId",
                  ),
                  body: {"komentar": controller.text},
                );

                if (response.statusCode == 200) {
                  await loadDetailResep();

                  if (!mounted) return;
                  Navigator.pop(context);
                }
              },
              child: const Text("Simpan"),
            ),
          ],
        );
      },
    );
  }

  Future<void> hapusKomentar(int komentarId) async {
    final response = await http.delete(
      Uri.parse("http://192.168.18.55:8000/api/komentars/$komentarId"),
    );

    if (response.statusCode == 200) {
      await loadDetailResep();

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Komentar berhasil dihapus")),
      );
    }
  }

  @override
  void initState() {
    super.initState();

    // SINKRONISASI ANTI-CRASH JIKA ARGUMEN ADALAH LIST
    if (widget.resep is List) {
      List listResep = widget.resep as List;
      resep = listResep.isNotEmpty ? Map.from(listResep[0]) : {};
    } else {
      resep = Map.from(widget.resep);
    }

    print(resep);
    getUser();
  }

  Future getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();

    currentUserId = prefs.getInt("user_id");
    currentUserName = prefs.getString("user_name");

    if (mounted) {
      setState(() {});
    }

    // SIMPAN RIWAYAT
    await http.post(
      Uri.parse("http://192.168.18.55:8000/api/riwayats"),
      body: {
        "user_id": currentUserId.toString(),
        "resep_id": resep['id'].toString(),
      },
    );

    await cekFavorit();
    await cekRating();
  }

  Future<void> loadDetailResep() async {
    final data = await ApiService.getDetailResep(resep['id']);
    if (!mounted) return;
    setState(() {
      if (data is List) {
        resep = data.isNotEmpty ? data[0] : {};
      } else {
        resep = data;
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    String gambar = "";

    if (resep['gambar'] != null) {
      gambar = resep['gambar'].toString();

      if (!gambar.startsWith('http')) {
        gambar = "http://192.168.18.55:8000/storage/$gambar";
      }
    }

    return Scaffold(
      backgroundColor: const Color(0xffF6F1EB),
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 320,
            pinned: true,
            backgroundColor: Colors.orange,
            flexibleSpace: FlexibleSpaceBar(
              background: Stack(
                fit: StackFit.expand,
                children: [
                  Image.network(
                    gambar,
                    fit: BoxFit.cover,
                    loadingBuilder: (context, child, progress) {
                      if (progress == null) return child;
                      return const Center(child: CircularProgressIndicator());
                    },
                    errorBuilder: (context, error, stackTrace) {
                      return Container(
                        color: Colors.red.shade100,
                        child: const Center(
                          child: Icon(Icons.broken_image, color: Colors.red),
                        ),
                      );
                    },
                  ),
                  Container(
                    decoration: const BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topCenter,
                        end: Alignment.bottomCenter,
                        colors: [
                          Colors.black45,
                          Colors.transparent,
                          Colors.black87,
                        ],
                      ),
                    ),
                  ),
                  Positioned(
                    left: 20,
                    right: 20,
                    bottom: 30,
                    child: Text(
                      resep['nama_resep'] ?? "",
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 28,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.star, color: Colors.amber),
                      const SizedBox(width: 5),
                      Text(
                        "${double.tryParse((resep['ratings_avg_rating'] ?? 0).toString())?.toStringAsFixed(1) ?? '0.0'}",
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(width: 10),
                      Text(
                        "(${resep['ratings_count'] ?? 0} rating)",
                        style: const TextStyle(color: Colors.grey),
                      ),
                    ],
                  ),
                  const SizedBox(height: 20),
                  const Text(
                    "Beri Rating",
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: List.generate(5, (index) {
                      return IconButton(
                        onPressed: () {
                          kirimRating(index + 1);
                        },
                        icon: Icon(
                          Icons.star,
                          color: userRating >= index + 1
                              ? Colors.amber
                              : Colors.grey,
                        ),
                      );
                    }),
                  ),
                  if (userRating > 0)
                    TextButton(
                      onPressed: hapusRating,
                      child: const Text(
                        "Hapus Rating",
                        style: TextStyle(color: Colors.red),
                      ),
                    ),
                  const SizedBox(height: 20),
                  Text(
                    resep['deskripsi'] ?? "",
                    style: const TextStyle(fontSize: 16, height: 1.6),
                  ),
                  const SizedBox(height: 25),
                  Row(
                    children: [
                      Expanded(
                        child: ElevatedButton.icon(
                          style: ElevatedButton.styleFrom(
                            backgroundColor: isFavorite
                                ? Colors.grey
                                : Colors.red,
                          ),
                          onPressed: () async {
                            if (!mounted) return;
                            await toggleFavorit();
                          },
                          icon: Icon(
                            isFavorite ? Icons.favorite : Icons.favorite_border,
                          ),
                          label: Text(isFavorite ? "Hapus Favorit" : "Favorit"),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 30),
                  const Text(
                    "🥬 Bahan-bahan",
                    style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 10),
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(20),
                      child: Text(resep['bahan'] ?? "Belum ada data bahan"),
                    ),
                  ),
                  const SizedBox(height: 25),
                  const Text(
                    "👨‍🍳 Langkah Memasak",
                    style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 10),
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(20),
                      child: Text(resep['langkah'] ?? "Belum ada langkah"),
                    ),
                  ),
                  const SizedBox(height: 25),
                  const Text(
                    "💬 Komentar",
                    style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 15),
                  TextField(
                    controller: komentarController,
                    maxLines: 4,
                    decoration: InputDecoration(
                      hintText: "Tulis komentar...",
                      filled: true,
                      fillColor: Colors.white,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(20),
                        borderSide: BorderSide.none,
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.orange,
                        padding: const EdgeInsets.symmetric(vertical: 15),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                        ),
                      ),
                      onPressed: () async {
                        await kirimKomentar();
                      },
                      child: const Text(
                        "Kirim Komentar",
                        style: TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  ListView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: resep['komentars'] != null
                        ? resep['komentars'].length
                        : 0,
                    itemBuilder: (context, index) {
                      final komentar = resep['komentars'][index];

                      return Card(
                        margin: const EdgeInsets.only(bottom: 10),
                        child: Padding(
                          padding: const EdgeInsets.all(15),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    komentar['user']['name'] ?? 'User',
                                    style: const TextStyle(
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  if (currentUserId == komentar['user_id'])
                                    Row(
                                      children: [
                                        IconButton(
                                          icon: const Icon(
                                            Icons.edit,
                                            color: Colors.orange,
                                          ),
                                          onPressed: () {
                                            editKomentar(
                                              komentar['id'],
                                              komentar['komentar'],
                                            );
                                          },
                                        ),
                                        IconButton(
                                          icon: const Icon(
                                            Icons.delete,
                                            color: Colors.red,
                                          ),
                                          onPressed: () {
                                            hapusKomentar(komentar['id']);
                                          },
                                        ),
                                      ],
                                    ),
                                ],
                              ),
                              const SizedBox(height: 5),
                              Text(komentar['komentar'] ?? ''),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
                  Text("Jumlah komentar: ${resep['komentars']?.length ?? 0}"),
                  const SizedBox(height: 50),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
