<?php

namespace App\Filament\Resources\Pembayarans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_id')
                    ->required()
                    ->numeric(),
                TextInput::make('order_id')
                    ->required(),
                TextInput::make('transaction_id')
                    ->default(null),
                TextInput::make('gross_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('transaction_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('fraud_status')
                    ->default(null),
                TextInput::make('payment_type')
                    ->default(null),
                TextInput::make('payment_gateway')
                    ->required()
                    ->default('midtrans'),
                TextInput::make('payment_gateway_reference_id')
                    ->default(null),
                TextInput::make('bank')
                    ->default(null),
                TextInput::make('va_number')
                    ->default(null),
                Textarea::make('qr_url')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('deeplink_url')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('payment_url')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('payment_gateway_response')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('midtrans_response')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('payment_proof')
                    ->default(null),
                DateTimePicker::make('payment_date'),
                DateTimePicker::make('transaction_time'),
                DateTimePicker::make('settlement_time'),
                DateTimePicker::make('expired_at'),
            ]);
    }
}
