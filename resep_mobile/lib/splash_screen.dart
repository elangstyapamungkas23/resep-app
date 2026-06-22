import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkSession();
  }

  Future<void> _checkSession() async {
    // ⏱️ Nunggu 3 detik di halaman splash
    await Future.delayed(const Duration(seconds: 3));

    final prefs = await SharedPreferences.getInstance();
    // ✅ Membaca kunci 'token' yang SAMA PERSIS dengan yang disave di login_page.dart lo
    final String? token = prefs.getString('token');

    if (mounted) {
      if (token != null) {
        Navigator.pushReplacementNamed(context, '/home');
      } else {
        Navigator.pushReplacementNamed(context, '/login');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xffF6F1EB), // Warna krem bawaan login lo
      body: Center(
        child: SizedBox(
          width: 400, // 🔥 UKURAN DIUBAH JADI MAKSIMAL 400
          height: 400, // 🔥 TINGGI JUGA JADI 400
          child: Image.asset(
            'lib/assets/images/logo.png',
            fit: BoxFit
                .contain, // Logo proporsional, gak bakal gepeng atau pecah
            errorBuilder: (context, error, stackTrace) {
              return const Icon(
                Icons.restaurant_menu,
                size: 120,
                color: Colors.orange,
              );
            },
          ),
        ),
      ),
    );
  }
}
