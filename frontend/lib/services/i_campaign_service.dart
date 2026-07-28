import '../models/campaign.dart';

/// This interface defines WHAT the service can do
/// It doesn't care HOW it does it (mock vs API)
abstract class ICampaignService {
  //campaigns
  Future<List<Campaign>> getCampaigns();
  
  Future<Campaign> getCampaign({required String id});

  // Donations
  Future<Map<String, dynamic>> initiateSTKPush({
    required String campaignId,
    required String phoneNumber,
    required double amount,
    
  });
  
  Future<Map<String, dynamic>> checkSTKStatus({
    required String checkoutRequestId,
    required String donationId,
    
  });
}