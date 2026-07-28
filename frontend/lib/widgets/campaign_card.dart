import 'package:flutter/material.dart';
import 'package:fundyetu/widgets/fade_page_route.dart';
import '../models/campaign.dart';
import '../screens/donation_form_screen.dart';
import '../config/constants.dart';
import '../config/app_colors.dart';

class CampaignCard extends StatelessWidget {
  final Campaign campaign;

  const CampaignCard({super.key, required this.campaign});

  @override
  Widget build(BuildContext context) {
    return Hero(
      tag: 'campaign_${campaign.id}',
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.grey.withValues(alpha: 0.1),
              spreadRadius: 2,
              blurRadius: 8,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // ---- Image with Category Badge ----
            ClipRRect(
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(16),
                topRight: Radius.circular(16),
              ),
              child: Stack(
                children: [
                  Image.network(
                    campaign.imageUrl.isNotEmpty
                        ? campaign.imageUrl
                        : AppConstants.defaultCampaignImage,
                    height: 180,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    errorBuilder: (_, _, _) => Container(
                      height: 180,
                      width: double.infinity,
                      color: Colors.grey[200],
                      child: const Icon(
                        Icons.volunteer_activism,
                        size: 50,
                        color: Colors.grey,
                      ),
                    ),
                  ),
                  // Category badge (navy, top-left)
                  if (campaign.category.isNotEmpty)
                    Positioned(
                      top: 12,
                      left: 12,
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 10,
                          vertical: 4,
                        ),
                        decoration: BoxDecoration(
                          color: AppColors.navy,
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Text(
                          campaign.category,
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 11,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                ],
              ),
            ),

            // ---- Content ----
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Title
                  Text(
                    campaign.title,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Colors.black87,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 4),
                  // "by user" (if you have user name from API)
                  // Text(
                  //   'by ${campaign.userName ?? "Unknown"}',
                  //   style: TextStyle(
                  //     fontSize: 12,
                  //     color: Colors.grey[400],
                  //   ),
                  // ),
                  const SizedBox(height: 12),

                  // ---- Progress Bar ----
                  Row(
                    children: [
                      Expanded(
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(12),
                          child: LinearProgressIndicator(
                            value: campaign.progress / 100,
                            minHeight: 8,
                            backgroundColor: Colors.grey[200],
                            color: AppColors.maroon, // ✅ maroon
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      Text(
                        '${campaign.progress.toInt()}%',
                        style: const TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                          color: AppColors.maroon,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 6),

                  // ---- Raised and Goal ----
                  Row(
                    children: [
                      Text(
                        campaign.formattedRaised,
                        style: const TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                          color: AppColors.maroon, // ✅ maroon
                        ),
                      ),
                      const SizedBox(width: 4),
                      Text(
                        'raised of ${campaign.formattedTarget} goal',
                        style: TextStyle(
                          fontSize: 12,
                          color: Colors.grey[600],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),

                  // ---- Donate Button ----
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
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        backgroundColor: AppColors.primaryOrange,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(30),
                        ),
                        elevation: 4,
                        shadowColor: AppColors.primaryOrange.withValues(alpha: 0.3),
                      ),
                      child: const Text(
                        'Donate Now',
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}