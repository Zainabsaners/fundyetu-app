import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/campaign.dart';
import '../providers/campaign_provider.dart';
import '../config/app_colors.dart';
import '../config/constants.dart';
import 'donation_form_screen.dart';
import '../widgets/fade_page_route.dart';

class CampaignDetailScreen extends StatefulWidget {
  final String campaignId;
  const CampaignDetailScreen({super.key, required this.campaignId});

  @override
  State<CampaignDetailScreen> createState() => _CampaignDetailScreenState();
}

class _CampaignDetailScreenState extends State<CampaignDetailScreen> {
  bool _isLoading = true;
  String? _error;
  Campaign? _campaign;

  @override
  void initState() {
    super.initState();
    _loadCampaign();
  }

  Future<void> _loadCampaign() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final provider = Provider.of<CampaignProvider>(context, listen: false);
      final campaign = await provider.getCampaignById(widget.campaignId);
      if (campaign != null) {
        setState(() => _campaign = campaign);
      } else {
        setState(() => _error = 'Campaign not found');
      }
    } catch (e) {
      setState(() => _error = e.toString());
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        appBar: AppBar(title: const Text('Campaign Details')),
        body: const Center(child: CircularProgressIndicator()),
      );
    }
    if (_error != null || _campaign == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Campaign Details')),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 60, color: Colors.red),
              const SizedBox(height: 16),
              Text(_error ?? 'Campaign not found'),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Go Back'),
              ),
            ],
          ),
        ),
      );
    }

    final campaign = _campaign!;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Campaign Details'),
        backgroundColor: AppColors.navy,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image with Hero
            Hero(
              tag: 'campaign_${campaign.id}',
              child: ClipRRect(
                borderRadius: BorderRadius.circular(12),
                child: Image.network(
                  campaign.imageUrl.isNotEmpty
                      ? campaign.imageUrl
                      : AppConstants.defaultCampaignImage,
                  height: 200,
                  width: double.infinity,
                  fit: BoxFit.contain,
                  errorBuilder: (_, _, _) => Container(
                    height: 200,
                    width: double.infinity,
                    color: Colors.grey[200],
                    child: const Icon(Icons.image_not_supported, size: 40),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 16),

            // Category badge
            if (campaign.category.isNotEmpty)
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                decoration: BoxDecoration(
                  color: AppColors.navy,
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  campaign.category,
                  style: const TextStyle(color: Colors.white, fontSize: 12),
                ),
              ),
            const SizedBox(height: 8),

            // Title
            Text(
              campaign.title,
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),

            // Description (story)
            Text(
              campaign.description,
              style: const TextStyle(fontSize: 16, height: 1.5),
            ),
            const SizedBox(height: 16),

            // Progress bar
            const Text('Progress', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            Row(
              children: [
                Expanded(
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(8),
                    child: LinearProgressIndicator(
                      value: campaign.progress / 100,
                      minHeight: 10,
                      backgroundColor: Colors.grey[300],
                      color: AppColors.maroon,
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                Text('${campaign.progress.toInt()}%'),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              '${campaign.formattedRaised} raised of ${campaign.formattedTarget} goal',
              style: const TextStyle(fontSize: 14),
            ),
            const SizedBox(height: 16),

            // Stats: Donors, days left
            Row(
              children: [
                const Icon(Icons.people, size: 16),
                const SizedBox(width: 4),
                Text('${campaign.donorCount} donors'),
                const SizedBox(width: 16),
                const Icon(Icons.timer, size: 16),
                const SizedBox(width: 4),
                Text('${campaign.daysRemaining} days left'),
              ],
            ),
            const SizedBox(height: 24),

            // Donate Button (full width, at bottom)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.push(
                    context,
                    FadePageRoute(
                      child: DonationFormScreen(
                        campaignId: campaign.id,
                        campaignTitle: campaign.title,
                      ),
                    ),
                  );
                },
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  backgroundColor: AppColors.primaryOrange,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(30),
                  ),
                  elevation: 4,
                ),
                child: const Text(
                  'Donate Now',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}