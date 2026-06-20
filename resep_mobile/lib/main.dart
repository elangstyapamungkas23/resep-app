import 'package:flutter/material.dart';
import 'package:resep_mobile/pages/favorite_page.dart';
import 'pages/login_page.dart';
import 'pages/register_page.dart';
import 'pages/home_page.dart';
import 'pages/resep_page.dart';
import 'pages/detail_page.dart';
import 'pages/profile_page.dart';
import 'pages/create_page.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,

      initialRoute: '/login',

      routes: {
        '/login': (context) => const LoginPage(),
        '/register': (context) => const RegisterPage(),
        '/home': (context) => const MainNavigation(),
      },

      onGenerateRoute: (settings) {
        if (settings.name == '/detail') {
          final resep = settings.arguments as Map;

          return MaterialPageRoute(builder: (_) => DetailPage(resep: resep));
        }

        return null;
      },
    );
  }
}

class MainNavigation extends StatefulWidget {
  const MainNavigation({super.key});

  @override
  State<MainNavigation> createState() => _MainNavigationState();
}

class _MainNavigationState extends State<MainNavigation> {
  int currentIndex = 0;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: [
        const HomePage(),
        const ResepPage(),
        const CreatePage(),
        const FavoritePage(),
        const ProfilePage(),
      ][currentIndex],

      bottomNavigationBar: BottomNavigationBar(
        currentIndex: currentIndex,
        selectedItemColor: Colors.orange,
        unselectedItemColor: Colors.grey,
        type: BottomNavigationBarType.fixed,

        onTap: (index) {
          setState(() {
            currentIndex = index;
          });
        },

        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: "Home"),

          BottomNavigationBarItem(
            icon: Icon(Icons.restaurant_menu),
            label: "Resep",
          ),

          BottomNavigationBarItem(
            icon: Icon(Icons.add_circle, size: 38),
            label: "",
          ),

          BottomNavigationBarItem(icon: Icon(Icons.favorite), label: "Favorit"),

          BottomNavigationBarItem(icon: Icon(Icons.person), label: "Profil"),
        ],
      ),
    );
  }
}
