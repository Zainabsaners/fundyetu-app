import 'dart:async';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/campaign_provider.dart';
import 'donation_result_screen.dart';
import '../config/app_colors.dart';

class DonationStatusScreen extends StatefulWidget {
  final String checkoutRequestId;
  final String donationId;
  final double amount;
  final String campaignId;
  final String campaignTitle;
  final String phoneNumber;
  final String donorName;
  final String donorEmail;

  const DonationStatusScreen({
    super.key,
    required this.checkoutRequestId,
    required this.donationId,
    required this.amount,
    required this.campaignId,
    required this.campaignTitle,
    required this.phoneNumber,
    required this.donorName,
    required this.donorEmail,
  });

  @override
  State<DonationStatusScreen> createState() => _DonationStatusScreenState();
}

class _DonationStatusScreenState extends State<DonationStatusScreen> {
  Timer? _timer;
  int _attempts = 0;
  static const int maxAttempts = 30;

  @override
  void initState() {
    super.initState();
    _startPolling();
  }

  void _startPolling() {
    _timer = Timer.periodic(const Duration(seconds: 3), (timer) {
      _attempts++;
      _checkStatus();

      if (_attempts >= maxAttempts) {
        timer.cancel();
        _navigateToResult(
          success: false,
          message: 'Payment request timed out. Please check your M-Pesa transactions.',
        );
      }
    });
  }

  Future<void> _checkStatus() async {
    try {
      final provider = Provider.of<CampaignProvider>(context, listen: false);
      final response = await provider.checkDonationStatus(
        donationId: widget.donationId,
        checkoutRequestId: widget.checkoutRequestId,
      );

      final status = response['status']?.toString().toLowerCase() ?? '';

      if (status == 'completed' || status == 'success') {
        _timer?.cancel();
        
        // Personalized Thank You Message including donor name & campaign title
        final donorDisplay = widget.donorName.isNotEmpty ? widget.donorName : 'Valued Donor';
        final thankYouMessage = 'Thank you so much, $donorDisplay! Your generous contribution of KES ${widget.amount.toStringAsFixed(0)} towards "${widget.campaignTitle}" was successful. 🎉';

        _navigateToResult(
          success: true,
          message: thankYouMessage,
          transactionId: response['transactionId'],
        );
      } else if (status == 'failed' || status == 'cancelled') {
        _timer?.cancel();
        _navigateToResult(
          success: false,
          message: response['resultDescription'] ?? 'Payment was not completed.',
        );
      }
    } catch (e) {
      // Ignore errors – keep polling
    }
  }

  void _navigateToResult({
    required bool success,
    required String message,
    String? transactionId,
  }) {
    if (!mounted) return;

    Navigator.pushReplacement(
      context,
      MaterialPageRoute(
        builder: (context) => DonationResultScreen(
          isSuccess: success,
          message: message,
          transactionId: transactionId,
          amount: widget.amount,
        ),
      ),
    );
  }

  @override
  void dispose() {
    _timer?.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Processing Payment'),
        backgroundColor: AppColors.lightBlue, // Uses your brand blue color theme
        foregroundColor: Colors.white,       // Ensures text/icons are clear and visible
        automaticallyImplyLeading: false,
      ),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const SizedBox(
              height: 80,
              width: 80,
              child: CircularProgressIndicator(
                strokeWidth: 4,
                color: AppColors.primaryOrange, // Updated to match your theme accent
              ),
            ),
            const SizedBox(height: 32),
            const Text(
              'STK Push Sent!',
              style: TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 12),
            const Text(
              'Check your phone for the M-Pesa PIN prompt and enter your PIN to complete payment.',
              style: TextStyle(fontSize: 16, color: Colors.grey),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 8),
            const Text(
              'Waiting for payment confirmation...',
              style: TextStyle(fontSize: 14, color: Colors.grey),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 16),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.blue.shade50,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Column(
                children: [
                  const Text(
                    'Amount',
                    style: TextStyle(fontSize: 12, color: Colors.grey),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'KES ${widget.amount.toStringAsFixed(0)}',
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: AppColors.lightBlue,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),
            Text(
              'Waiting for confirmation...',
              style: TextStyle(
                fontSize: 14,
                color: Colors.grey[600],
              ),
            ),
            const SizedBox(height: 8),
            TextButton(
              onPressed: () {
                _timer?.cancel();
                Navigator.pop(context);
              },
              child: const Text('Cancel Payment', style: TextStyle(color: AppColors.primaryOrange)),
            ),
          ],
        ),
      ),
    );
  }
}