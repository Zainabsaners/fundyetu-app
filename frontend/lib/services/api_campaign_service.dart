import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter/foundation.dart';
import '../models/campaign.dart';
import 'i_campaign_service.dart';
import '../config/constants.dart';
//import '../config/environment.dart';

/// Real API implementation - Connects to Laravel backend
class ApiCampaignService implements ICampaignService {
  // Base URL - Replace with your actual backend URL
  //static const String baseUrl = Environment.baseUrl;
  
  // For local development with Laravel
   static const String baseUrl = AppConstants.baseUrl;

  // Headers for API requests
  Map<String, String> _headers({String? token}) {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (token != null) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  // ========== CAMPAIGNS ==========

  @override
  Future<List<Campaign>> getCampaigns() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/campaigns'),
        headers: _headers(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        
        // Handle different API response structures
        List<dynamic> campaignsData;
        if (data['data'] != null) {
          campaignsData = data['data'];
        } else if (data['campaigns'] != null) {
          campaignsData = data['campaigns'];
        } else if (data is List) {
          campaignsData = data;
        } else {
          campaignsData = [];
        }

        return campaignsData
            .map((json) => Campaign.fromJson(json))
            .toList();
      } else if (response.statusCode == 404) {
        // No campaigns found
        return [];
      } else {
        throw Exception('Failed to load campaigns: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  @override
  Future<Campaign> getCampaign({required String id}) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/campaigns/$id'),
        headers: _headers(),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final campaignData = data['data'] ?? data;
        return Campaign.fromJson(campaignData);
      } else if (response.statusCode == 404) {
        throw Exception('Campaign not found');
      } else {
        throw Exception('Failed to load campaign: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  // ========== DONATIONS ==========

  @override
  Future<Map<String, dynamic>> initiateSTKPush({
    required String campaignId,
    required String phoneNumber,
    required double amount,
  }) async {
    final body = {
      'amount': amount,
      'donor_phone': phoneNumber,
      'payment_method': 'mpesa',
      'donor_name': '',
      'donor_email': '',
    };
    debugPrint('Donation request body: $body');
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/campaigns/$campaignId/donate'),
        headers: _headers(),
        body: jsonEncode({
          'amount': amount,
          'donor_phone': phoneNumber,      // ✅ correct field name
          'donor_name': '',                // optional
          'donor_email': '',               // optional
          'payment_method': 'mpesa'
          
         
        }),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        return jsonDecode(response.body);
      } else {
        final errorData = jsonDecode(response.body);
        throw Exception(errorData['message'] ?? 'STK Push failed');
      }
    } catch (e) {
      throw Exception('Payment error: $e');
    }
  }

  @override
  Future<Map<String, dynamic>> checkSTKStatus({
    required String checkoutRequestId,
    required String donationId,
  }) async {
    try {
      debugPrint('Checking status for donation: $donationId');
      debugPrint('URL: $baseUrl/donations/$donationId/status');
      final response = await http.get(
        Uri.parse('$baseUrl/donations/$donationId/status'),
        headers: _headers(),
        
      );
      debugPrint('Status code: ${response.statusCode}');
      debugPrint('Status body: ${response.body}');

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        final errorData = jsonDecode(response.body);
        throw Exception(errorData['message'] ?? 'Failed to check status');
      }
    } catch (e) {
      throw Exception('Status check error: $e');
    }
  }
}