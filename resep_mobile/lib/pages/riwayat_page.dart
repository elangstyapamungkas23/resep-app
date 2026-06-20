import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class RiwayatPage extends StatefulWidget {
  const RiwayatPage({super.key});

  @override
  State<RiwayatPage> createState() => _RiwayatPageState();
}

class _RiwayatPageState extends State<RiwayatPage> {
  List riwayat = [];
  bool loading = true;
  int? userId;

  @override
  void initState() {
    super.initState();
    getRiwayat();
  }

  Future<void> getRiwayat() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    userId = prefs.getInt("user_id");

    try {
      final response = await http.get(
        Uri.parse("http://192.168.18.55:8000/api/riwayats"),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        if (!mounted) return;

        setState(() {
          var listData = data['data'] ?? [];

          riwayat = listData
              .where((e) => e != null && e['user_id'] == userId)
              .toList();

          loading = false;
        });
      }
    } catch (e) {
      print("ERROR RIWAYAT: $e");
      if (!mounted) return;
      setState(() {
        loading = false;
      });
    }
  }

  Future<void> hapusSemua() async {
    for (var item in riwayat) {
      await http.delete(
        Uri.parse("http://192.168.18.55:8000/api/riwayats/${item['id']}"),
      );
    }

    getRiwayat();

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text("Semua riwayat berhasil dihapus")),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Riwayat"),
        backgroundColor: Colors.orange,
        actions: [
          if (riwayat.isNotEmpty)
            IconButton(
              onPressed: hapusSemua,
              icon: const Icon(Icons.delete_forever),
            ),
        ],
      ),
      body: loading
          ? const Center(child: CircularProgressIndicator())
          : riwayat.isEmpty
          ? const Center(
              child: Text("Belum ada riwayat", style: TextStyle(fontSize: 18)),
            )
          : ListView.builder(
              itemCount: riwayat.length,
              itemBuilder: (context, index) {
                final item = riwayat[index];
                final resep = item['resep'];

                return Card(
                  margin: const EdgeInsets.all(10),
                  child: ListTile(
                    leading: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(
                        "http://192.168.18.55:8000/storage/${resep['gambar']}",
                        width: 60,
                        height: 60,
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) =>
                            const Icon(Icons.image),
                      ),
                    ),
                    title: Text(resep['nama_resep'] ?? '-'),
                    subtitle: Text(
                      resep['deskripsi'] ?? '',
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                );
              },
            ),
    );
  }
}
