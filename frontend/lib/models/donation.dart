import 'package:hive/hive.dart';
import 'package:uuid/uuid.dart';

part 'donation.g.dart';

@HiveType(typeId: 1)
enum DonationStatus {
  @HiveField(0)
  pending,
  @HiveField(1)
  completed,
  @HiveField(2)
  failed,
}

@HiveType(typeId: 0)
class Donation extends HiveObject {
  @HiveField(0)
  String id;

  @HiveField(1)
  String campaignId;

  @HiveField(2)
  String campaignTitle;

  @HiveField(3)
  double amount;

  @HiveField(4)
  String phoneNumber;

  @HiveField(5)
  String donorName;

  @HiveField(6)
  String donorEmail;

  @HiveField(7)
  DonationStatus status;

  @HiveField(8)
  DateTime createdAt;

  @HiveField(9)
  String? transactionId;

  @HiveField(10)
  String? checkoutRequestId;

  Donation({
    String? id,
    required this.campaignId,
    required this.campaignTitle,
    required this.amount,
    required this.phoneNumber,
    required this.donorName,
    required this.donorEmail,
    this.status = DonationStatus.pending,
    this.transactionId,
    this.checkoutRequestId,
  })  : id = id ?? const Uuid().v4(),
        createdAt = DateTime.now();

  // Helper: Get status display text
  String get statusDisplay {
    switch (status) {
      case DonationStatus.pending:
        return '⏳ Pending';
      case DonationStatus.completed:
        return '✅ Completed';
      case DonationStatus.failed:
        return '❌ Failed';
    }
  }

  // Helper: Get status color
  String get statusColor {
    switch (status) {
      case DonationStatus.pending:
        return '#FFA500'; // Orange
      case DonationStatus.completed:
        return '#00C853'; // Green
      case DonationStatus.failed:
        return '#FF1744'; // Red
    }
  }

  // Helper: Format date
  String get formattedDate {
    return '${createdAt.day}/${createdAt.month}/${createdAt.year} ${createdAt.hour.toString().padLeft(2, '0')}:${createdAt.minute.toString().padLeft(2, '0')}';
  }

  // Helper: Format amount
  String get formattedAmount {
    return 'KES ${amount.toStringAsFixed(0).replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (match) => '${match[1]},')}';
  }

  // Convert to JSON (for API)
  Map<String, dynamic> toJson() => {
    'id': id,
    'campaignId': campaignId,
    'campaignTitle': campaignTitle,
    'amount': amount,
    'phoneNumber': phoneNumber,
    'donorName': donorName,
    'donorEmail': donorEmail,
    'status': status.name,
    'createdAt': createdAt.toIso8601String(),
    'transactionId': transactionId,
    'checkoutRequestId': checkoutRequestId,
  };

  // Create from JSON
  factory Donation.fromJson(Map<String, dynamic> json) => Donation(
    id: json['id'],
    campaignId: json['campaignId'],
    campaignTitle: json['campaignTitle'],
    amount: json['amount'].toDouble(),
    phoneNumber: json['phoneNumber'],
    donorName: json['donorName'],
    donorEmail: json['donorEmail'],
    status: DonationStatus.values.firstWhere(
      (e) => e.name == json['status'],
      orElse: () => DonationStatus.pending,
    ),
    transactionId: json['transactionId'],
    checkoutRequestId: json['checkoutRequestId'],
  );
}