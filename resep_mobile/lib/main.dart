import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(debugShowCheckedModeBanner: false, home: ResepPage());
  }
}

class ResepPage extends StatefulWidget {
  @override
  State<ResepPage> createState() => _ResepPageState();
}

class _ResepPageState extends State<ResepPage> {
  List dataResep = [];

  Future getData() async {
    final response = await http.get(
      Uri.parse('http://10.0.2.2:8000/api/reseps'),
      headers: {
        'Authorization':
            'Bearer 15|fY4qeJysc3DJHIaGnICIlPOxslQuriWH17DLPwmKe166b41d',
      },
    );

    final data = jsonDecode(response.body);

    setState(() {
      dataResep = data['data'];
    });
  }

  @override
  void initState() {
    super.initState();
    getData();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Data Resep")),

      body: ListView.builder(
        itemCount: dataResep.length,
        itemBuilder: (context, index) {
          return ListTile(
            title: Text(dataResep[index]['nama_resep']),
            subtitle: Text(dataResep[index]['deskripsi']),
          );
        },
      ),
    );
  }
}
