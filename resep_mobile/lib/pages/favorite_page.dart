import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'detail_page.dart';

class FavoritePage extends StatefulWidget {
  const FavoritePage({super.key});

  @override
  State<FavoritePage> createState() => _FavoritePageState();
}

class _FavoritePageState extends State<FavoritePage> {
  List favorit = [];

  int? userId;

  @override
  void initState() {
    super.initState();
    getUser();
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    getFavorit();
  }

  Future getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();

    userId = prefs.getInt('user_id');

    getFavorit();
  }

  Future getFavorit() async {
    final response = await http.get(
      Uri.parse("http://192.168.18.55:8000/api/favorits"),
    );

    final data = jsonDecode(response.body);

    if (!mounted) return;

    setState(() {
      favorit = (data['data'] ?? [])
          .where((e) => e['user_id'] == userId)
          .toList();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Favorit"), backgroundColor: Colors.red),

      body: favorit.isEmpty
          ? const Center(child: Text("Belum ada resep favorit"))
          : ListView.builder(
              itemCount: favorit.length,
              itemBuilder: (context, index) {
                final item = favorit[index];

                if (item == null || item['resep'] == null) {
                  return const SizedBox();
                }

                final resep = item['resep'];

                return Card(
                  margin: const EdgeInsets.all(10),
                  child: ListTile(
                    leading: Image.network(
                      resep['gambar'] ?? '',
                      width: 60,
                      fit: BoxFit.cover,
                      errorBuilder: (_, __, ___) => const Icon(Icons.image),
                    ),
                    title: Text(resep['nama_resep'] ?? ''),
                    subtitle: Text(
                      resep['deskripsi'] ?? '',
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => DetailPage(resep: resep),
                        ),
                      );
                    },
                  ),
                );
              },
            ),
    );
  }
}
