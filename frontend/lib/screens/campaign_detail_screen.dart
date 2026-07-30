import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:share_plus/share_plus.dart';
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

class _CampaignDetailScreenState extends State<CampaignDetailScreen>
    with SingleTickerProviderStateMixin {
  bool _isLoading = true;
  String? _error;
  Campaign? _campaign;
  late TabController _tabController;

  final TextEditingController _customAmountController = TextEditingController();
  int? _selectedPresetAmount;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _loadCampaign();
  }

  @override
  void dispose() {
    _tabController.dispose();
    _customAmountController.dispose();
    super.dispose();
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
    final gallery = campaign.media.where((m) => m['collection_name'] == 'gallery').toList();
    final video = campaign.media.firstWhere(
      (m) => m['collection_name'] == 'video',
      orElse: () => null,
    );

    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        title: const Text('Campaign Details'),
        backgroundColor: AppColors.navy,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.share),
            onPressed: () {
              Share.share(
                'Check out this fundraiser: ${campaign.title}\n\n'
                'https://fundyetu-api.onrender.com/campaigns/${campaign.id}',
                subject: campaign.title,
              );
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // 1. Dark Web-Style Header Banner
            _buildWebStyleHeader(campaign),

            // 2. Hero Image, Action Buttons, Progress Bar & KPIs
            _buildHeroSection(campaign),

            const SizedBox(height: 12),

            // 3. Tab Section Container
            Container(
              color: Colors.white,
              child: Column(
                children: [
                  TabBar(
                    controller: _tabController,
                    indicatorColor: AppColors.primaryOrange,
                    labelColor: AppColors.primaryOrange,
                    unselectedLabelColor: Colors.grey,
                    tabs: const [
                      Tab(text: 'Description'),
                      Tab(text: 'Video'),
                      Tab(text: 'Gallery'),
                      Tab(text: 'Comments'),
                    ],
                  ),
                  SizedBox(
                    height: 250,
                    child: TabBarView(
                      controller: _tabController,
                      children: [
                        _buildDescriptionTab(campaign),
                        _buildVideoTab(video),
                        _buildGalleryTab(gallery),
                        _buildCommentsTab(campaign),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // 4. Inline Web-Style M-Pesa Donation Section
            _buildInlineDonationSection(campaign),

            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }

  // --- DARK WEB-STYLE HEADER BANNER ---
  Widget _buildWebStyleHeader(Campaign campaign) {
    return Container(
      padding: const EdgeInsets.all(16),
      color: AppColors.navy,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            campaign.title,
            style: const TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 8),
          Wrap(
            spacing: 12,
            runSpacing: 4,
            children: const [
              Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.check_circle, size: 14, color: Colors.greenAccent),
                  SizedBox(width: 4),
                  Text('Verified', style: TextStyle(color: Colors.white70, fontSize: 11)),
                ],
              ),
              Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.people, size: 14, color: Colors.white70),
                  SizedBox(width: 4),
                  Text('4 Donors', style: TextStyle(color: Colors.white70, fontSize: 11)),
                ],
              ),
              Text('Treasurer Controlled: No', style: TextStyle(color: Colors.white70, fontSize: 11)),
            ],
          ),
        ],
      ),
    );
  }

  // --- HERO SECTION ---
  Widget _buildHeroSection(Campaign campaign) {
    return Container(
      padding: const EdgeInsets.all(16),
      color: Colors.white,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Hero(
            tag: 'campaign_${campaign.id}',
            child: ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.network(
                campaign.imageUrl.isNotEmpty
                    ? campaign.imageUrl
                    : AppConstants.defaultCampaignImage,
                height: 180,
                width: double.infinity,
                fit: BoxFit.contain,
                errorBuilder: (_, _, _) => Container(
                  height: 180,
                  width: double.infinity,
                  color: Colors.grey[200],
                  child: const Icon(Icons.image_not_supported, size: 50),
                ),
              ),
            ),
          ),
          const SizedBox(height: 14),

          // Donate & Share Buttons Row
          Row(
            children: [
              Expanded(
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
                    backgroundColor: AppColors.primaryOrange,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  ),
                  child: const Text('DONATE', style: TextStyle(fontWeight: FontWeight.bold)),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: OutlinedButton(
                  onPressed: () {
                    Share.share('Check out this fundraiser: ${campaign.title}');
                  },
                  style: OutlinedButton.styleFrom(
                    side: BorderSide(color: Colors.amber.shade700),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  ),
                  child: Text('SHARE', style: TextStyle(color: Colors.amber.shade800, fontWeight: FontWeight.bold)),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),

          // --- PROGRESS BAR & PERCENTAGE (Restored) ---
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    '${campaign.formattedRaised} raised of ${campaign.formattedTarget}',
                    style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500, color: Colors.grey),
                  ),
                  Text(
                    '${campaign.progress.toStringAsFixed(1)}%',
                    style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.black87),
                  ),
                ],
              ),
              const SizedBox(height: 6),
              ClipRRect(
                borderRadius: BorderRadius.circular(8),
                child: LinearProgressIndicator(
                  value: (campaign.progress / 100).clamp(0.0, 1.0),
                  minHeight: 8,
                  backgroundColor: Colors.grey[200],
                  color: AppColors.primaryOrange,
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),

          // KPI Metrics Box
          Container(
            padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 8),
            decoration: BoxDecoration(
              color: Colors.grey[100],
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.grey.shade200),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                Column(
                  children: [
                    Text(
                      campaign.formattedRaised,
                      style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: AppColors.maroon),
                    ),
                    const SizedBox(height: 2),
                    const Text('Funds Raised', style: TextStyle(fontSize: 10, color: Colors.grey)),
                  ],
                ),
                Column(
                  children: [
                    Text(
                      '${campaign.donorCount}',
                      style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.black87),
                    ),
                    const SizedBox(height: 2),
                    const Text('Donors', style: TextStyle(fontSize: 10, color: Colors.grey)),
                  ],
                ),
                const Column(
                  children: [
                    Text(
                      '30',
                      style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.black87),
                    ),
                    SizedBox(height: 2),
                    Text('Days Left', style: TextStyle(fontSize: 10, color: Colors.grey)),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // --- INLINE M-PESA DONATION SECTION ---
  Widget _buildInlineDonationSection(Campaign campaign) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(color: Colors.grey.withValues(alpha: 0.1), blurRadius: 6, spreadRadius: 1),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(vertical: 10),
            decoration: const BoxDecoration(
              color: Colors.green,
              borderRadius: BorderRadius.only(
                topLeft: Radius.circular(12),
                topRight: Radius.circular(12),
              ),
            ),
            child: const Text(
              'Select donation method',
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: Colors.green.shade50,
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: Colors.green.shade200),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: const [
                      Icon(Icons.phone_android, size: 14, color: Colors.green),
                      SizedBox(width: 6),
                      Text('M-Pesa', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.green, fontSize: 12)),
                    ],
                  ),
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: [2300, 2000, 7500].map((amount) {
                    final isSelected = _selectedPresetAmount == amount;
                    return OutlinedButton(
                      onPressed: () {
                        setState(() {
                          _selectedPresetAmount = amount;
                          _customAmountController.text = amount.toString();
                        });
                      },
                      style: OutlinedButton.styleFrom(
                        side: BorderSide(color: isSelected ? AppColors.primaryOrange : Colors.grey.shade300),
                        backgroundColor: isSelected ? Colors.amber.shade50 : Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                      child: Text('$amount', style: const TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)),
                    );
                  }).toList(),
                ),
                const SizedBox(height: 16),
                const Text('Custom amount', style: TextStyle(fontSize: 11, color: Colors.grey)),
                const SizedBox(height: 6),
                TextField(
                  controller: _customAmountController,
                  keyboardType: TextInputType.number,
                  decoration: InputDecoration(
                    prefixText: 'KES ',
                    filled: true,
                    fillColor: Colors.grey[100],
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(10),
                      borderSide: BorderSide.none,
                    ),
                    contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  ),
                ),
                const SizedBox(height: 16),
                ElevatedButton(
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
                    backgroundColor: AppColors.primaryOrange,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  ),
                  child: const Text('DONATE NOW', style: TextStyle(fontWeight: FontWeight.bold)),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // --- DESCRIPTION TAB ---
  Widget _buildDescriptionTab(Campaign campaign) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Text(
        campaign.description,
        style: const TextStyle(fontSize: 14, height: 1.5, color: Colors.black87),
      ),
    );
  }

  // --- VIDEO TAB ---
  Widget _buildVideoTab(dynamic video) {
    if (video == null || (video['url'] ?? '').isEmpty) {
      return const Center(child: Text('No video available.', style: TextStyle(fontSize: 12)));
    }
    return const Center(child: Text('Video player placeholder', style: TextStyle(fontSize: 12)));
  }

  // --- GALLERY TAB ---
  Widget _buildGalleryTab(List<dynamic> gallery) {
    if (gallery.isEmpty) {
      return const Center(child: Text('No gallery images.', style: TextStyle(fontSize: 12)));
    }
    return GridView.builder(
      padding: const EdgeInsets.all(8),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 3,
        crossAxisSpacing: 4,
        mainAxisSpacing: 4,
      ),
      itemCount: gallery.length,
      itemBuilder: (context, index) {
        final image = gallery[index];
        final url = image['url'] ?? '';
        return ClipRRect(
          borderRadius: BorderRadius.circular(8),
          child: Image.network(
            url,
            fit: BoxFit.contain,
            errorBuilder: (_, _, _) => Container(
              color: Colors.grey[200],
              child: const Icon(Icons.image_not_supported, size: 20),
            ),
          ),
        );
      },
    );
  }

  // --- COMMENTS TAB ---
  Widget _buildCommentsTab(Campaign campaign) {
    final comments = campaign.comments;
    if (comments.isEmpty) {
      return const Center(child: Text('No comments yet.', style: TextStyle(fontSize: 12)));
    }
    return ListView.builder(
      padding: const EdgeInsets.all(8),
      itemCount: comments.length,
      itemBuilder: (context, index) {
        final comment = comments[index];
        final user = comment['user'] ?? {};
        final name = user['name'] ?? 'Anonymous';
        final body = comment['body'] ?? '';
        return ListTile(
          dense: true,
          leading: CircleAvatar(child: Text(name[0].toUpperCase(), style: const TextStyle(fontSize: 12))),
          title: Text(name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
          subtitle: Text(body, style: const TextStyle(fontSize: 11)),
        );
      },
    );
  }
}