// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'donation.dart';

// **************************************************************************
// TypeAdapterGenerator
// **************************************************************************

class DonationAdapter extends TypeAdapter<Donation> {
  @override
  final int typeId = 0;

  @override
  Donation read(BinaryReader reader) {
    final numOfFields = reader.readByte();
    final fields = <int, dynamic>{
      for (int i = 0; i < numOfFields; i++) reader.readByte(): reader.read(),
    };
    return Donation(
      id: fields[0] as String?,
      campaignId: fields[1] as String,
      campaignTitle: fields[2] as String,
      amount: fields[3] as double,
      phoneNumber: fields[4] as String,
      donorName: fields[5] as String,
      donorEmail: fields[6] as String,
      status: fields[7] as DonationStatus,
      transactionId: fields[9] as String?,
      checkoutRequestId: fields[10] as String?,
    )..createdAt = fields[8] as DateTime;
  }

  @override
  void write(BinaryWriter writer, Donation obj) {
    writer
      ..writeByte(11)
      ..writeByte(0)
      ..write(obj.id)
      ..writeByte(1)
      ..write(obj.campaignId)
      ..writeByte(2)
      ..write(obj.campaignTitle)
      ..writeByte(3)
      ..write(obj.amount)
      ..writeByte(4)
      ..write(obj.phoneNumber)
      ..writeByte(5)
      ..write(obj.donorName)
      ..writeByte(6)
      ..write(obj.donorEmail)
      ..writeByte(7)
      ..write(obj.status)
      ..writeByte(8)
      ..write(obj.createdAt)
      ..writeByte(9)
      ..write(obj.transactionId)
      ..writeByte(10)
      ..write(obj.checkoutRequestId);
  }

  @override
  int get hashCode => typeId.hashCode;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is DonationAdapter &&
          runtimeType == other.runtimeType &&
          typeId == other.typeId;
}

class DonationStatusAdapter extends TypeAdapter<DonationStatus> {
  @override
  final int typeId = 1;

  @override
  DonationStatus read(BinaryReader reader) {
    switch (reader.readByte()) {
      case 0:
        return DonationStatus.pending;
      case 1:
        return DonationStatus.completed;
      case 2:
        return DonationStatus.failed;
      default:
        return DonationStatus.pending;
    }
  }

  @override
  void write(BinaryWriter writer, DonationStatus obj) {
    switch (obj) {
      case DonationStatus.pending:
        writer.writeByte(0);
        break;
      case DonationStatus.completed:
        writer.writeByte(1);
        break;
      case DonationStatus.failed:
        writer.writeByte(2);
        break;
    }
  }

  @override
  int get hashCode => typeId.hashCode;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is DonationStatusAdapter &&
          runtimeType == other.runtimeType &&
          typeId == other.typeId;
}
