import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/donation_provider.dart';
import '../models/donation.dart';

class DonationHistoryScreen extends StatefulWidget {
  const DonationHistoryScreen({super.key});

  @override
  State<DonationHistoryScreen> createState() => _DonationHistoryScreenState();
}

class _DonationHistoryScreenState extends State<DonationHistoryScreen> {
  @override
  Widget build(BuildContext context) {
    // Get the provider using the Consumer widget
    return Consumer<DonationProvider>(
      builder: (context, provider, child) {
        final donations = provider.donations;

        return Scaffold(
          appBar: AppBar(
            title: const Text('Donation History'),
            backgroundColor: Theme.of(context).colorScheme.inversePrimary,
            actions: [
              if (donations.isNotEmpty)
                Padding(
                  padding: const EdgeInsets.only(right: 16),
                  child: Center(
                    child: Text(
                      'KES ${provider.totalDonated.toStringAsFixed(0)}',
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: Colors.teal,
                      ),
                    ),
                  ),
                ),
            ],
          ),
          body: _buildBody(donations, provider),
        );
      },
    );
  }

  Widget _buildBody(List<Donation> donations, DonationProvider provider) {
    if (donations.isEmpty) {
      return _buildEmptyState();
    }

    return Column(
      children: [
        // Stats summary
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.teal[50],
            border: Border(
              bottom: BorderSide(
                color: Colors.grey[200]!,
                width: 1,
              ),
            ),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildStatItem(
                icon: Icons.attach_money,
                label: 'Total Donated',
                value: 'KES ${provider.totalDonated.toStringAsFixed(0)}',
                color: Colors.teal,
              ),
              _buildStatItem(
                icon: Icons.check_circle,
                label: 'Successful',
                value: provider.totalDonations.toString(),
                color: Colors.green,
              ),
              _buildStatItem(
                icon: Icons.hourglass_empty,
                label: 'Pending',
                value: provider.pendingDonations.length.toString(),
                color: Colors.orange,
              ),
            ],
          ),
        ),
        // Donation list
        Expanded(
          child: ListView.builder(
            padding: const EdgeInsets.all(8),
            itemCount: donations.length,
            itemBuilder: (context, index) {
              final donation = donations[index];
              return _buildDonationCard(donation);
            },
          ),
        ),
      ],
    );
  }

  Widget _buildStatItem({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return Column(
      children: [
        Row(
          children: [
            Icon(icon, size: 16, color: color),
            const SizedBox(width: 4),
            Text(
              value,
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: color,
              ),
            ),
          ],
        ),
        Text(
          label,
          style: TextStyle(
            fontSize: 11,
            color: Colors.grey[600],
          ),
        ),
      ],
    );
  }

  Widget _buildEmptyState() {
    return const Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.history,
            size: 80,
            color: Colors.grey,
          ),
          SizedBox(height: 16),
          Text(
            'No Donations Yet',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
            ),
          ),
          SizedBox(height: 8),
          Text(
            'Your donation history will appear here.',
            style: TextStyle(
              fontSize: 14,
              color: Colors.grey,
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildDonationCard(Donation donation) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 4, horizontal: 8),
      child: ListTile(
        contentPadding: const EdgeInsets.all(12),
        leading: CircleAvatar(
          backgroundColor: _getStatusColor(donation.status),
          child: Icon(
            _getStatusIcon(donation.status),
            color: Colors.white,
            size: 20,
          ),
        ),
        title: Row(
          children: [
            Expanded(
              child: Text(
                donation.campaignTitle,
                style: const TextStyle(
                  fontWeight: FontWeight.bold,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ),
            Text(
              donation.formattedAmount,
              style: const TextStyle(
                fontWeight: FontWeight.bold,
                color: Colors.teal,
              ),
            ),
          ],
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Phone: ${donation.phoneNumber}',
              style: const TextStyle(fontSize: 12),
            ),
            Text(
              donation.formattedDate,
              style: const TextStyle(fontSize: 11, color: Colors.grey),
            ),
          ],
        ),
        trailing: Container(
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
          decoration: BoxDecoration(
            color: _getStatusColor(donation.status).withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Text(
            donation.statusDisplay,
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w600,
              color: _getStatusColor(donation.status),
            ),
          ),
        ),
        isThreeLine: true,
        onTap: () {
          _showDonationDetail(donation);
        },
      ),
    );
  }

  void _showDonationDetail(Donation donation) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: Text(donation.campaignTitle),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildDetailRow('Amount', donation.formattedAmount),
              _buildDetailRow('Status', donation.statusDisplay),
              _buildDetailRow('Phone', donation.phoneNumber),
              _buildDetailRow('Donor', donation.donorName),
              _buildDetailRow('Email', donation.donorEmail),
              _buildDetailRow('Date', donation.formattedDate),
              if (donation.transactionId != null)
                _buildDetailRow('Transaction ID', donation.transactionId!),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Close'),
            ),
          ],
        );
      },
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 90,
            child: Text(
              label,
              style: const TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 12,
                color: Colors.grey,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 13),
            ),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(DonationStatus status) {
    switch (status) {
      case DonationStatus.pending:
        return Colors.orange;
      case DonationStatus.completed:
        return Colors.green;
      case DonationStatus.failed:
        return Colors.red;
    }
  }

  IconData _getStatusIcon(DonationStatus status) {
    switch (status) {
      case DonationStatus.pending:
        return Icons.hourglass_empty;
      case DonationStatus.completed:
        return Icons.check;
      case DonationStatus.failed:
        return Icons.close;
    }
  }
}