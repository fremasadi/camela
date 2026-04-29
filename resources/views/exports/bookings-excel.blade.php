{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:html="http://www.w3.org/TR/REC-html40">
    <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
        <Author>Camela</Author>
        <Created>{{ now()->toAtomString() }}</Created>
    </DocumentProperties>

    <Styles>
        <Style ss:ID="Default" ss:Name="Normal">
            <Alignment ss:Vertical="Center"/>
            <Borders/>
            <Font ss:FontName="Calibri" ss:Size="11"/>
            <Interior/>
            <NumberFormat/>
            <Protection/>
        </Style>

        <Style ss:ID="Title">
            <Font ss:Bold="1" ss:Size="14"/>
        </Style>

        <Style ss:ID="Label">
            <Font ss:Bold="1"/>
        </Style>

        <Style ss:ID="Header">
            <Font ss:Bold="1"/>
            <Interior ss:Color="#D9EAF7" ss:Pattern="Solid"/>
            <Borders>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>

        <Style ss:ID="Cell">
            <Borders>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>

        <Style ss:ID="Currency">
            <Borders>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
            <NumberFormat ss:Format="&quot;Rp&quot; #,##0"/>
        </Style>
    </Styles>

    <Worksheet ss:Name="Bookings">
        <Table>
            <Column ss:AutoFitWidth="0" ss:Width="120"/>
            <Column ss:AutoFitWidth="0" ss:Width="140"/>
            <Column ss:AutoFitWidth="0" ss:Width="140"/>
            <Column ss:AutoFitWidth="0" ss:Width="90"/>
            <Column ss:AutoFitWidth="0" ss:Width="70"/>
            <Column ss:AutoFitWidth="0" ss:Width="90"/>
            <Column ss:AutoFitWidth="0" ss:Width="110"/>
            <Column ss:AutoFitWidth="0" ss:Width="110"/>
            <Column ss:AutoFitWidth="0" ss:Width="110"/>

            <Row>
                <Cell ss:StyleID="Title"><Data ss:Type="String">Laporan Booking</Data></Cell>
            </Row>
            <Row>
                <Cell ss:StyleID="Label"><Data ss:Type="String">Periode</Data></Cell>
                <Cell ss:MergeAcross="2"><Data ss:Type="String">{{ $tanggalDari ? \Carbon\Carbon::parse($tanggalDari)->format('d/m/Y') : '-' }} s/d {{ $tanggalSampai ? \Carbon\Carbon::parse($tanggalSampai)->format('d/m/Y') : '-' }}</Data></Cell>
            </Row>
            <Row>
                <Cell ss:StyleID="Label"><Data ss:Type="String">Pencarian</Data></Cell>
                <Cell ss:MergeAcross="2"><Data ss:Type="String">{{ filled($search) ? $search : '-' }}</Data></Cell>
            </Row>
            <Row>
                <Cell ss:StyleID="Label"><Data ss:Type="String">Tanggal Export</Data></Cell>
                <Cell ss:MergeAcross="2"><Data ss:Type="String">{{ now()->format('d/m/Y H:i') }}</Data></Cell>
            </Row>
            <Row>
                <Cell ss:StyleID="Label"><Data ss:Type="String">Total Booking</Data></Cell>
                <Cell><Data ss:Type="Number">{{ $bookings->count() }}</Data></Cell>
            </Row>
            <Row>
                <Cell ss:StyleID="Label"><Data ss:Type="String">Total Pendapatan</Data></Cell>
                <Cell ss:StyleID="Currency"><Data ss:Type="Number">{{ (float) $totalPendapatan }}</Data></Cell>
            </Row>
            <Row/>
            <Row>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Order ID</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Pelanggan</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Pegawai</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Tanggal</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Jam</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Status</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Pembayaran</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Total Harga</Data></Cell>
                <Cell ss:StyleID="Header"><Data ss:Type="String">Total Bayar</Data></Cell>
            </Row>

            @forelse ($bookings as $booking)
                <Row>
                    <Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $booking->order_id }}</Data></Cell>
                    <Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $booking->user->name ?? '-' }}</Data></Cell>
                    <Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $booking->pegawai->name ?? '-' }}</Data></Cell>
                    <Cell ss:StyleID="Cell"><Data ss:Type="String">{{ optional($booking->tanggal_booking)->format('d/m/Y') }}</Data></Cell>
                    <Cell ss:StyleID="Cell"><Data ss:Type="String">{{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }}</Data></Cell>
                    <Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $booking->status }}</Data></Cell>
                    <Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $booking->jenis_pembayaran }}</Data></Cell>
                    <Cell ss:StyleID="Currency"><Data ss:Type="Number">{{ (float) $booking->total_harga }}</Data></Cell>
                    <Cell ss:StyleID="Currency"><Data ss:Type="Number">{{ (float) $booking->total_pembayaran }}</Data></Cell>
                </Row>
            @empty
                <Row>
                    <Cell ss:StyleID="Cell" ss:MergeAcross="8"><Data ss:Type="String">Data booking tidak ada.</Data></Cell>
                </Row>
            @endforelse
        </Table>
    </Worksheet>
</Workbook>
