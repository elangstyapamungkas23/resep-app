import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  static String baseUrl = "http://192.168.18.55:8000/api";

  static Future<List> getResep() async {
    final response = await http.get(Uri.parse("$baseUrl/reseps"));

    final data = jsonDecode(response.body);

    return data['data'];
  }

  static Future<Map<String, dynamic>> getDetailResep(int id) async {
    final response = await http.get(Uri.parse("$baseUrl/reseps/$id"));

    final data = jsonDecode(response.body);

    return data['data'];
  }
}
