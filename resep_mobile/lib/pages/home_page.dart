import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../services/api_service.dart';
import '../pages/resep_page.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  List resep = [];
  bool loading = true;

  @override
  void initState() {
    super.initState();
    getResep();
  }

  Future<void> getResep() async {
    try {
      final response = await http.get(
        Uri.parse("http://192.168.18.55:8000/api/reseps"),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        setState(() {
          resep = data['data'] ?? [];
          loading = false;
        });
      }
    } catch (e) {
      print(e);

      setState(() {
        loading = false;
      });
    }
  }

  String getImage(dynamic gambar) {
    if (gambar == null) {
      return "";
    }

    return gambar.toString();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xffFFF7F1),

      body: loading
          ? const Center(child: CircularProgressIndicator())
          : CustomScrollView(
              slivers: [
                SliverAppBar(
                  floating: true,
                  backgroundColor: const Color(0xffFFF7F1),
                  elevation: 0,

                  title: const Row(
                    children: [
                      Text("🍜", style: TextStyle(fontSize: 28)),
                      SizedBox(width: 8),
                      Text(
                        "Recipe App",
                        style: TextStyle(
                          color: Colors.deepOrange,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ),
                ),

                SliverToBoxAdapter(
                  child: Padding(
                    padding: const EdgeInsets.all(20),

                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,

                      children: [
                        const Text(
                          "Temukan Resep Favoritmu",
                          style: TextStyle(
                            fontSize: 34,
                            fontWeight: FontWeight.bold,
                          ),
                        ),

                        const SizedBox(height: 10),

                        const Text(
                          "Jelajahi berbagai resep makanan modern dan tradisional",
                          style: TextStyle(color: Colors.grey),
                        ),

                        const SizedBox(height: 20),

                        Container(
                          height: 220,

                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(30),

                            image: const DecorationImage(
                              image: NetworkImage(
                                "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200",
                              ),
                              fit: BoxFit.cover,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                const SliverToBoxAdapter(
                  child: Padding(
                    padding: EdgeInsets.symmetric(horizontal: 20),
                    child: Text(
                      "Kategori",
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),

                SliverToBoxAdapter(
                  child: SizedBox(
                    height: 120,

                    child: ListView(
                      scrollDirection: Axis.horizontal,

                      children: [
                        GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => const ResepPage(kategoriId: 1),
                              ),
                            );
                          },
                          child: kategoriCard(
                            "🍜",
                            "Makanan",
                            Colors.orange.shade100,
                          ),
                        ),

                        GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => const ResepPage(kategoriId: 2),
                              ),
                            );
                          },
                          child: kategoriCard(
                            "☕",
                            "Minuman",
                            Colors.blue.shade100,
                          ),
                        ),

                        GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => const ResepPage(kategoriId: 3),
                              ),
                            );
                          },
                          child: kategoriCard(
                            "🍰",
                            "Dessert",
                            Colors.pink.shade100,
                          ),
                        ),

                        GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => const ResepPage(kategoriId: 4),
                              ),
                            );
                          },
                          child: kategoriCard(
                            "🥗",
                            "Healthy",
                            Colors.green.shade100,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                const SliverToBoxAdapter(
                  child: Padding(
                    padding: EdgeInsets.all(20),

                    child: Text(
                      "Resep Populer",
                      style: TextStyle(
                        fontSize: 28,
                        color: Colors.deepOrange,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),

                SliverPadding(
                  padding: const EdgeInsets.symmetric(horizontal: 20),

                  sliver: SliverList(
                    delegate: SliverChildBuilderDelegate((context, index) {
                      final item = resep[index];

                      print("URL GAMBAR DARI API:");
                      print(item['gambar']);

                      return Container(
                        margin: const EdgeInsets.only(bottom: 20),

                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(25),

                          boxShadow: [
                            BoxShadow(
                              color: Colors.black12,
                              blurRadius: 10,
                              offset: const Offset(0, 5),
                            ),
                          ],
                        ),

                        child: InkWell(
                          borderRadius: BorderRadius.circular(25),

                          onTap: () async {
                            try {
                              final detail = await ApiService.getDetailResep(
                                item['id'],
                              );

                              Navigator.pushNamed(
                                context,
                                '/detail',
                                arguments: detail,
                              );
                            } catch (e) {
                              print(e);

                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text("Gagal memuat detail resep"),
                                ),
                              );
                            }
                          },

                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,

                            children: [
                              ClipRRect(
                                borderRadius: const BorderRadius.vertical(
                                  top: Radius.circular(25),
                                ),

                                child: Image.network(
                                  item['gambar'].toString(),
                                  width: double.infinity,
                                  height: 220,
                                  fit: BoxFit.cover,

                                  errorBuilder: (context, error, stackTrace) {
                                    return Container(
                                      height: 220,
                                      color: Colors.grey.shade300,

                                      child: const Center(
                                        child: Icon(Icons.image, size: 60),
                                      ),
                                    );
                                  },
                                ),
                              ),

                              Padding(
                                padding: const EdgeInsets.all(16),

                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,

                                  children: [
                                    Text(
                                      item['nama_resep'] ?? '',
                                      style: const TextStyle(
                                        fontSize: 22,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),

                                    const SizedBox(height: 8),

                                    Text(
                                      item['deskripsi'] ?? '',
                                      maxLines: 2,
                                      overflow: TextOverflow.ellipsis,
                                    ),

                                    const SizedBox(height: 10),

                                    Row(
                                      children: [
                                        const Icon(
                                          Icons.star,
                                          color: Colors.orange,
                                        ),

                                        const SizedBox(width: 5),

                                        Text(
                                          "${double.tryParse((item['ratings_avg_rating'] ?? 0).toString())?.toStringAsFixed(1) ?? '0.0'}",
                                          style: const TextStyle(
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),

                                        const SizedBox(width: 8),

                                        Text(
                                          "(${item['ratings_count'] ?? 0} rating)",
                                          style: const TextStyle(
                                            color: Colors.grey,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                      );
                    }, childCount: resep.length > 3 ? 3 : resep.length),
                  ),
                ),
              ],
            ),
    );
  }

  Widget kategoriCard(String emoji, String title, Color color) {
    return Container(
      width: 90,
      margin: const EdgeInsets.all(10),

      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(20),
      ),

      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,

        children: [
          Text(emoji, style: const TextStyle(fontSize: 30)),

          const SizedBox(height: 5),

          Text(
            title,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
          ),
        ],
      ),
    );
  }
}
