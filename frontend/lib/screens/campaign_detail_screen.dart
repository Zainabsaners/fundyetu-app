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

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _loadCampaign();
  }

  @override
  void dispose() {
    _tabController.dispose();
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

    // Extract gallery and video from media
    final gallery = campaign.media.where((m) => m['collection_name'] == 'gallery').toList();
    final video = campaign.media.firstWhere(
      (m) => m['collection_name'] == 'video',
      orElse: () => null,
    );

    return Scaffold(
      appBar: AppBar(
        title: const Text('Campaign Details'),
        backgroundColor: AppColors.navy,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.share),
            onPressed: () async {
              await Share.share(
                'Check out this fundraiser: ${campaign.title}\n\n'
                'https://fundyetu-api.onrender.com/campaigns/${campaign.id}',
                subject: campaign.title,
              );
            },
          ),
        ],
      ),
      body: Column(
        children: [
          // Hero section (image, title, stats, progress, donate)
          _buildHeroSection(campaign),
          // TabBar
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
          // TabBarView
          Expanded(
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
      floatingActionButton: SizedBox(
        width: 200,
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
            padding: const EdgeInsets.symmetric(vertical: 14),
            backgroundColor: AppColors.primaryOrange,
            foregroundColor: Colors.white,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(30),
            ),
          ),
          child: const Text(
            'Donate Now',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
        ),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
    );
  }

  // --- Hero Section ---
  Widget _buildHeroSection(Campaign campaign) {
    return Container(
      padding: const EdgeInsets.all(16),
      color: Colors.white,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Hero Image
          Hero(
            tag: 'campaign_${campaign.id}',
            child: ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.network(
                campaign.imageUrl.isNotEmpty
                    ? campaign.imageUrl
                    : AppConstants.defaultCampaignImage,
                height: 160,
                width: double.infinity,
                fit: BoxFit.contain,
                errorBuilder: (_, _, _) => Container(
                  height: 160,
                  width: double.infinity,
                  color: Colors.grey[200],
                  child: const Icon(Icons.image_not_supported, size: 50),
                ),
              ),
            ),
          ),
          const SizedBox(height: 12),
          // Title
          Text(
            campaign.title,
            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 4),
          // Category
          if (campaign.category.isNotEmpty)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
              decoration: BoxDecoration(
                color: AppColors.navy,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                campaign.category,
                style: const TextStyle(color: Colors.white, fontSize: 11),
              ),
            ),
          const SizedBox(height: 10),
          // Progress bar
          Row(
            children: [
              Expanded(
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(8),
                  child: LinearProgressIndicator(
                    value: campaign.progress / 100,
                    minHeight: 8,
                    backgroundColor: Colors.grey[300],
                    color: AppColors.maroon,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              Text('${campaign.progress.toInt()}%'),
            ],
          ),
          const SizedBox(height: 4),
          Row(
            children: [
              Text(
                campaign.formattedRaised,
                style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              ),
              const SizedBox(width: 4),
              Text(
                'raised of ${campaign.formattedTarget} goal',
                style: TextStyle(fontSize: 12, color: Colors.grey[600]),
              ),
            ],
          ),
          const SizedBox(height: 4),
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
        ],
      ),
    );
  }

  // --- Description Tab ---
  Widget _buildDescriptionTab(Campaign campaign) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Story',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          Text(
            campaign.description,
            style: const TextStyle(fontSize: 16, height: 1.5),
          ),
        ],
      ),
    );
  }

  // --- Video Tab ---
  Widget _buildVideoTab(dynamic video) {
    if (video == null) {
      return const Center(
        child: Padding(
          padding: EdgeInsets.all(24),
          child: Text('No video available.'),
        ),
      );
    }
    // Check if it's a URL or a file
    final videoUrl = video['url'] ?? '';
    if (videoUrl.isEmpty) {
      return const Center(child: Text('No video available.'));
    }
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: AspectRatio(
          aspectRatio: 16 / 9,
          child: Container(
            decoration: BoxDecoration(
              color: Colors.black,
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Center(
              child: Text(
                'Video player placeholder',
                style: TextStyle(color: Colors.white),
              ),
            ),
          ),
        ),
      ),
    );
  }

  // --- Gallery Tab ---
  Widget _buildGalleryTab(List<dynamic> gallery) {
    if (gallery.isEmpty) {
      return const Center(
        child: Text('No gallery images.'),
      );
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
        return Image.network(
          url,
          fit: BoxFit.cover,
          errorBuilder: (_, _, _) => Container(
            color: Colors.grey[200],
            child: const Icon(Icons.image_not_supported),
          ),
        );
      },
    );
  }

  // --- Comments Tab ---
  Widget _buildCommentsTab(Campaign campaign) {
    final comments = campaign.comments;
    if (comments.isEmpty) {
      return const Center(
        child: Text('No comments yet. Be the first to comment!'),
      );
    }
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: comments.length,
      itemBuilder: (context, index) {
        final comment = comments[index];
        final user = comment['user'] ?? {};
        final name = user['name'] ?? 'Anonymous';
        final body = comment['body'] ?? '';
        final createdAt = comment['created_at'] ?? '';
        return ListTile(
          leading: CircleAvatar(
            child: Text(name[0].toUpperCase()),
          ),
          title: Text(name, style: const TextStyle(fontWeight: FontWeight.bold)),
          subtitle: Text(body),
          trailing: Text(
            createdAt.toString().substring(0, 10),
            style: const TextStyle(fontSize: 10, color: Colors.grey),
          ),
        );
      },
    );
  }
}