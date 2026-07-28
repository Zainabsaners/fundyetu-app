import 'package:flutter/material.dart';
import 'package:hive_flutter/hive_flutter.dart';
import '../models/donation.dart';

class DonationProvider extends ChangeNotifier {
  // Hive box
  Box<Donation>? _donationBox;

  // List of all donations
  List<Donation> _donations = [];

  // Getters
  List<Donation> get donations => _donations;
  List<Donation> get completedDonations => _donations.where((d) => d.status == DonationStatus.completed).toList();
  List<Donation> get pendingDonations => _donations.where((d) => d.status == DonationStatus.pending).toList();
  List<Donation> get failedDonations => _donations.where((d) => d.status == DonationStatus.failed).toList();

  // Total donated amount
  double get totalDonated => _donations
      .where((d) => d.status == DonationStatus.completed)
      .fold(0.0, (sum, d) => sum + d.amount);

  // Total number of successful donations
  int get totalDonations => completedDonations.length;

  // Initialize the provider
  void init() {
    debugPrint('DonationProvider: init() called');
    _loadDonations();
  }

  // Load donations from Hive
  void _loadDonations() {
  try {
    _donationBox = Hive.box<Donation>('donation');
    _donations = _donationBox?.values.toList() ?? [];
    _sortDonations();
    debugPrint('DonationProvider: Loaded ${_donations.length} donations');
    notifyListeners();
  } catch (e) {
    debugPrint('DonationProvider: Error loading donations: $e');
  }
}

  

  // Sort donations by date (newest first)
  void _sortDonations() {
    _donations.sort((a, b) => b.createdAt.compareTo(a.createdAt));
  }

  // Add a new donation
  void addDonation(Donation donation) {
    _donationBox?.put(donation.id, donation);
    _donations.insert(0, donation); // Insert at top (newest first)
    notifyListeners();
  }

  // Update an existing donation
  void updateDonation(Donation updatedDonation) {
    _donationBox?.put(updatedDonation.id, updatedDonation);
    final index = _donations.indexWhere((d) => d.id == updatedDonation.id);
    if (index != -1) {
      _donations[index] = updatedDonation;
      _sortDonations();
    }
    notifyListeners();
  }

  // Get donation by ID
  Donation? getDonationById(String id) {
    try {
      return _donations.firstWhere((d) => d.id == id);
    } catch (e) {
      return null;
    }
  }

  // Get donations by phone number
  List<Donation> getDonationsByPhone(String phoneNumber) {
    return _donations.where((d) => d.phoneNumber == phoneNumber).toList();
  }

  // Clear all donations (for testing)
  void clearAllDonations() {
    _donationBox?.clear();
    _donations.clear();
    notifyListeners();
  }

  // Delete a single donation
  void deleteDonation(String id) {
    _donationBox?.delete(id);
    _donations.removeWhere((d) => d.id == id);
    notifyListeners();
  }
}