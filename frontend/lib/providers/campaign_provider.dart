import 'package:flutter/material.dart';
import '../models/campaign.dart';
import '../services/i_campaign_service.dart';
//import '../services/mock_campaign_service.dart';
import '../services/api_campaign_service.dart';

class CampaignProvider extends ChangeNotifier {
  // ========== CONFIGURATION ==========
  // 🔧 TO SWITCH TO REAL API, CHANGE THIS ONE LINE:
  //static final ICampaignService _mockservice = MockCampaignService();
  static final ICampaignService _apiservice = ApiCampaignService();

  // ====================================

  List<Campaign> _campaigns = [];
  bool _isLoading = false;
  String? _error;
  

  // Getters
  List<Campaign> get campaigns => _campaigns;
  bool get isLoading => _isLoading;
  String? get error => _error;



  // ========== CAMPAIGNS ==========

  Future<void> loadCampaigns() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _campaigns = await _apiservice.getCampaigns();
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<Campaign?> getCampaignById(String id) async {
    try {
      return await _apiservice.getCampaign(id: id);
    } catch (e) {
      try {
        final mockCampaigns = await _apiservice.getCampaigns();
        return mockCampaigns.firstWhere((c) => c.id == id);
      } catch (_) {
        return null;
      }
    }
  }

  Future<void> refreshCampaigns() async {
    await loadCampaigns();
  }

  // ========== DONATIONS ==========

  Future<Map<String, dynamic>> donate({
    required String campaignId,
    required String phoneNumber,
    required double amount,
  }) async {
   

    return await _apiservice.initiateSTKPush(
      campaignId: campaignId,
      phoneNumber: phoneNumber,
      amount: amount,
      
    );
  }

  Future<Map<String, dynamic>> checkDonationStatus({
    required String checkoutRequestId,
    required String donationId,
  }) async {
   

    return await _apiservice.checkSTKStatus(
      checkoutRequestId: checkoutRequestId,
      donationId: donationId,

      
    );
  }
}