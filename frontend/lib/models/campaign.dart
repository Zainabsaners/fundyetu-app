class Campaign {
  final String id;
  final String title;
  final String description;
  final String imageUrl;
  final double targetAmount;
  final double raisedAmount;
  final int donorCount;
  final DateTime endDate;
  final String category; // e.g., "Medical", "Education", "Community"

  Campaign({
    required this.id,
    required this.title,
    required this.description,
    required this.imageUrl,
    required this.targetAmount,
    required this.raisedAmount,
    required this.donorCount,
    required this.endDate,
    required this.category,
  });

  // Progress percentage
  double get progress => (raisedAmount / targetAmount) * 100;

  // Formatted amount
  String get formattedRaised => 'KES ${_formatNumber(raisedAmount)}';
  String get formattedTarget => 'KES ${_formatNumber(targetAmount)}';

  String _formatNumber(double value) {
    return value.toStringAsFixed(0).replaceAllMapped(
      RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
      (match) => '${match[1]},',
    );
  }

  // Days remaining
  int get daysRemaining {
    final now = DateTime.now();
    return endDate.difference(now).inDays;
  }

  // Convert to JSON (for API)
  Map<String, dynamic> toJson() => {
    'id': id,
    'title': title,
    'description': description,
    'imageUrl': imageUrl,
    'targetAmount': targetAmount,
    'raisedAmount': raisedAmount,
    'donorCount': donorCount,
    'endDate': endDate.toIso8601String(),
    'category': category,
  };

  // Create from JSON (from API)
  factory Campaign.fromJson(Map<String, dynamic> json) => Campaign(
    id: json['id'].toString(),
    title: json['title'],
    description: json['story'],
    imageUrl: json['image'] ?? '',
    targetAmount: double.parse(json['target_amount']?.toString() ?? '0'),
    raisedAmount: double.parse(json['raised_amount']?.toString() ?? '0'),
    donorCount: json['donor_count'] ?? 0,
    endDate: DateTime.parse(json['expiry_date']),
    category: json['category']['name'] ?? 'General',
  );
}