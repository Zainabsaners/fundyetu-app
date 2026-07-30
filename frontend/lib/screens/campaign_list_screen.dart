import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/campaign_provider.dart';
import '../models/campaign.dart';
import '../widgets/campaign_card.dart';
import '../widgets/shimmer_campaign_card.dart';
//import 'campaign_detail_screen.dart';
import 'donation_history_screen.dart';
import '../widgets/fade_page_route.dart';
import '../config/app_colors.dart';

class CampaignListScreen extends StatefulWidget {
  const CampaignListScreen({super.key});

  @override
  State<CampaignListScreen> createState() => _CampaignListScreenState();
}

class _CampaignListScreenState extends State<CampaignListScreen> {
  final ScrollController _scrollController = ScrollController();
  final TextEditingController _searchController = TextEditingController();
  
  // Search state
  String _searchQuery = '';
  String _selectedCategory = 'All';
  
  // Available categories
  final List<String> _categories = [
    'All',
    'Medical',
    'Education',
    'Community',
    'Business',
    'Emergency',
  ];

  @override
  void initState() {
    super.initState();
    
    // Load campaigns automatically
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final provider = Provider.of<CampaignProvider>(context, listen: false);
      provider.loadCampaigns();
    });
    
    // Listen for search changes
    _searchController.addListener(() {
      setState(() {
        _searchQuery = _searchController.text;
      });
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<CampaignProvider>(context);
    
    // Get filtered campaigns
    final filteredCampaigns = _getFilteredCampaigns(provider.campaigns);

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'FundYetu',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        backgroundColor: AppColors.navy,
        foregroundColor: Colors.white,
        actions: [
          // Donation History Button
          IconButton(
            icon: const Icon(Icons.history),
            onPressed: () {
              Navigator.push(
                context,
                FadePageRoute(
                  child: const DonationHistoryScreen(),
                ),
              );
            },
          ),
        ],
      ),
      body: Column(
        children: [
          // Search Bar
          _buildSearchBar(),
          
          // Category Filters
          _buildCategoryFilters(),
          
          // Campaign List
          Expanded(
            child: _buildBody(provider, filteredCampaigns),
          ),
        ],
      ),
    );
  }

  // ========== SEARCH BAR ==========
  Widget _buildSearchBar() {
    return Padding(
      padding: const EdgeInsets.all(12),
      child: TextField(
        controller: _searchController,
        decoration: InputDecoration(
          hintText: 'Search campaigns...',
          prefixIcon: const Icon(Icons.search),
          suffixIcon: _searchQuery.isNotEmpty
              ? IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    _searchController.clear();
                  },
                )
              : null,
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: BorderSide.none,
          ),
          filled: true,
          fillColor: Colors.grey[100],
          contentPadding: const EdgeInsets.symmetric(vertical: 0),
        ),
      ),
    );
  }

  // ========== CATEGORY FILTERS ==========
  Widget _buildCategoryFilters() {
    return SizedBox(
      height: 50,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 12),
        itemCount: _categories.length,
        itemBuilder: (context, index) {
          final category = _categories[index];
          final isSelected = _selectedCategory == category;
          
          return Padding(
            padding: const EdgeInsets.only(right: 8),
            child: FilterChip(
              label: Text(category),
              selected: isSelected,
              onSelected: (selected) {
                setState(() {
                  _selectedCategory = selected ? category : 'All';
                });
              },
              backgroundColor: Colors.grey[200],
              selectedColor: Colors.teal[100],
              labelStyle: TextStyle(
                color: isSelected ? Colors.teal : Colors.grey[700],
                fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
              ),
              checkmarkColor: Colors.teal,
            ),
          );
        },
      ),
    );
  }

  // ========== FILTER LOGIC ==========
  List<Campaign> _getFilteredCampaigns(List<Campaign> campaigns) {
    // Filter by search query
    var filtered = campaigns.where((campaign) {
      final query = _searchQuery.toLowerCase();
      return campaign.title.toLowerCase().contains(query) ||
          campaign.description.toLowerCase().contains(query);
    }).toList();
    
    // Filter by category
    if (_selectedCategory != 'All') {
      filtered = filtered
          .where((campaign) => campaign.category == _selectedCategory)
          .toList();
    }
    
    return filtered;
  }

  // ========== BODY ==========
  Widget _buildBody(CampaignProvider provider, List<Campaign> filteredCampaigns) {
    if (provider.isLoading) {
      return ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: 5,
        itemBuilder: (context, index) => const ShimmerCampaignCard(),
      );
    }

    if (provider.error != null) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline, size: 60, color: Colors.red),
            const SizedBox(height: 16),
            Text(
              'Something went wrong',
              style: TextStyle(
                fontSize: 18,
                color: Colors.grey[600],
              ),
            ),
            const SizedBox(height: 8),
            Text(
              provider.error!,
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey[500]),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: provider.refreshCampaigns,
              child: const Text('Try Again'),
            ),
          ],
        ),
      );
    }

    if (provider.campaigns.isEmpty) {
      return _buildEmptyState();
    }

    // No results after filtering
    if (filteredCampaigns.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.search_off, size: 60, color: Colors.grey),
            const SizedBox(height: 16),
            const Text(
              'No campaigns found',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Try adjusting your search or filters.',
              style: TextStyle(
                fontSize: 14,
                color: Colors.grey[600],
              ),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () {
                _searchController.clear();
                setState(() {
                  _selectedCategory = 'All';
                });
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.teal,
              ),
              child: const Text(
                'Clear Filters',
                style: TextStyle(color: Colors.white),
              ),
            ),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: provider.refreshCampaigns,
      child: ListView.builder(
        controller: _scrollController,
        padding: const EdgeInsets.all(16),
        itemCount: filteredCampaigns.length,
        itemBuilder: (context, index) {
          final campaign = filteredCampaigns[index];
          return CampaignCard(
              campaign: campaign,
          );
        },
      ),
    );
  }

  Widget _buildEmptyState() {
    return SingleChildScrollView(
      child: Center(
        child: Padding(
          padding: const EdgeInsets.symmetric(vertical: 40),
          child: Column( 
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.hourglass_empty, size: 80, color: Colors.grey),
              SizedBox(height: 16),
              Text(
                'No Campaigns Yet',
                style: TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                ),
              ),
              SizedBox(height: 8),
              Text(
                'Check back soon for new campaigns.',
                style: TextStyle(fontSize: 16, color: Colors.grey),
              ),
              SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }
}