import 'dart:io';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:image_picker/image_picker.dart';

class EditProfilePage extends StatefulWidget {
  final Map user;

  const EditProfilePage({super.key, required this.user});

  @override
  State<EditProfilePage> createState() => _EditProfilePageState();
}

class _EditProfilePageState extends State<EditProfilePage> {
  final _nameController = TextEditingController();
  final _bioController = TextEditingController();

  File? fotoProfil;
  File? fotoCover;

  @override
  void initState() {
    super.initState();

    _nameController.text = widget.user['name'] ?? '';
    _bioController.text = widget.user['bio'] ?? '';
  }

  Future pickFotoProfil() async {
    final picker = ImagePicker();

    final file = await picker.pickImage(source: ImageSource.gallery);

    if (file != null) {
      setState(() {
        fotoProfil = File(file.path);
      });
    }
  }

  Future pickFotoCover() async {
    final picker = ImagePicker();

    final file = await picker.pickImage(source: ImageSource.gallery);

    if (file != null) {
      setState(() {
        fotoCover = File(file.path);
      });
    }
  }

  Future simpanProfil() async {
    try {
      final prefs = await SharedPreferences.getInstance();

      final userId = prefs.getInt('user_id');

      var request = http.MultipartRequest(
        'POST',
        Uri.parse('http://192.168.18.55:8000/api/profile/update/$userId'),
      );

      request.fields['name'] = _nameController.text;
      request.fields['bio'] = _bioController.text;

      if (fotoProfil != null) {
        request.files.add(
          await http.MultipartFile.fromPath('foto', fotoProfil!.path),
        );
      }

      if (fotoCover != null) {
        request.files.add(
          await http.MultipartFile.fromPath('cover', fotoCover!.path),
        );
      }

      var response = await request.send();

      var responseBody = await response.stream.bytesToString();

      print(response.statusCode);
      print(responseBody);

      if (response.statusCode == 200) {
        if (!mounted) return;

        Navigator.pop(context, true);
      } else {
        if (!mounted) return;

        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Gagal update profil (${response.statusCode})"),
          ),
        );
      }
    } catch (e) {
      print(e);

      if (!mounted) return;

      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text("Error: $e")));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Edit Profil")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            TextField(
              controller: _nameController,
              decoration: const InputDecoration(labelText: "Nama"),
            ),

            const SizedBox(height: 20),

            TextField(
              controller: _bioController,
              maxLines: 4,
              decoration: const InputDecoration(labelText: "Bio"),
            ),

            const SizedBox(height: 20),

            ListTile(
              title: const Text("Foto Profil"),
              trailing: const Icon(Icons.image),
              onTap: pickFotoProfil,
            ),

            if (fotoProfil != null) Image.file(fotoProfil!, height: 120),

            const SizedBox(height: 20),

            ListTile(
              title: const Text("Foto Cover"),
              trailing: const Icon(Icons.image),
              onTap: pickFotoCover,
            ),

            if (fotoCover != null) Image.file(fotoCover!, height: 120),

            const SizedBox(height: 30),

            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: simpanProfil,
                child: const Text("Simpan Profil"),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
