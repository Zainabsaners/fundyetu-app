/// Environment configuration for the app
class Environment {
  // Base URLs
  static const String baseUrl = 'http://fundyetu.veritech.co.ke';
  // For local development:
  // static const String baseUrl = 'http://localhost:8000/api';

  // API Endpoints
  static const String campaignsEndpoint = '$baseUrl/campaigns';
  static const String donationEndpoint = '$baseUrl/donations';
  static const String stkPushEndpoint = '$donationEndpoint/stk-push';
  static const String stkStatusEndpoint = '$donationEndpoint/stk-status';

  // Timeouts
  static const Duration connectionTimeout = Duration(seconds: 30);
  static const Duration receiveTimeout = Duration(seconds: 30);
}