<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Feuille d'Absence Hebdomadaire</title>
  <style>
    @page {
        margin: 10mm 10mm 10mm 10mm; /* top right bottom left */
    }
  

    body {
      font-family: Arial, sans-serif;
      margin: 5px;
      font-size: 12px;
    }

    h1, h2 {
      text-align: center;
      margin: 0;
    }

    h1 {
      font-size: 18px;
      margin-bottom: 10px;
    }

    h2 {
      font-size: 16px;
      margin-bottom: 30px;
    }

    p {
      margin: 5px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-size: 12px;
    }

    th, td {
      border: 1px solid black;
      padding: 4px;
      vertical-align: middle;
      padding: 2px;
    }
    .no-border, .no-border td {
  border: none !important;
}
th{
  text-align: center;
}
.tdfor{
  font-size: 8px;
    padding: unset;
}
.rotated-name {
    display: inline-block;
}

.rotated-char {
    transform: rotate(90deg);
    display: block;
    line-height: 0.7;
}
#tableone{
  width:"100%";
  border:none;
}

  </style>
</head>
<body>
@php
    $path = public_path('img/logo.png');
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
@endphp

<table class="no-border" id="tableone" style="margin-bottom: 20px;">
  <tr>
    <td width="100">
      <img src="{{ $base64 }}" width="100" alt="Logo">
    </td>
    <td align="center">
      <h1 style="margin: 0;">FEUILLE D'ABSENCE HEBDOMADAIRE</h1>
      <h4 style="margin: 0;">{{ $group->efp }}</h4>
    </td>
  </tr>
</table>


  <table  class="no-border" style="width: 100%; margin-top: 20px; border-collapse: collapse; font-size: 13px;">
  <tr>
    <td style="padding: 4px; text-align: left;"><strong>Filière :</strong> {{ $group->filiere }} ({{ $group->annee_formation }}A)</td>
    <td style="padding: 4px; text-align: right;"><strong>Année de Formation :</strong> 2024-2025</td>
  </tr>
  <tr>
    <td style="padding: 4px; text-align: left;"><strong>Groupe :</strong> {{ $group->code_groupe }}{{ $group->num_groupe }} 2024-2025</td>
    <td style="padding: 4px; text-align: right;"><strong>Semaine du</strong> {{ \Carbon\Carbon::parse($weekStart)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($weekEnd)->format('d/m/Y') }}</td>
  </tr>
</table>


  <table>
    <thead>
      <tr>
        <th rowspan="2">N°</th>
        <th rowspan="2">Nom & Prénom</th>
        <th colspan="4">LUN</th>
        <th colspan="4">MAR</th>
        <th colspan="4">MERC</th>
        <th colspan="4">JEU</th>
        <th colspan="4">VEN</th>
        <th colspan="4">SAM</th>
      </tr>
      <tr>
        <th>1</th><th>2</th><th>3</th><th>4</th>
        <th>1</th><th>2</th><th>3</th><th>4</th>
        <th>1</th><th>2</th><th>3</th><th>4</th>
        <th>1</th><th>2</th><th>3</th><th>4</th>
        <th>1</th><th>2</th><th>3</th><th>4</th>
        <th>1</th><th>2</th><th>3</th><th>4</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($stagiaires as $index => $stagiaire)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $stagiaire->name }}</td>
        @for ($i = 1; $i <= 24; $i++)
            <td>
                @if(isset($absenceMap[$stagiaire->id][$i]))
                    A
                @endif
            </td>
        @endfor
    </tr>
    @endforeach
    <tr>
  <td colspan="2">Emargements des Formateurs :</td>
  @for ($i = 1; $i <= 24; $i++)
  <td class="tdfor">
    @if(isset($formateurs[$i]))
    @foreach ($formateurs[$i] as $name)
    <div class="rotated-name">
        @foreach (str_split($name) as $char)
            <div class="rotated-char">{{ $char }}</div>
        @endforeach
    </div>
@endforeach
    @endif
  </td>
  @endfor
</tr>
    </tbody>
  </table>

  <table class="no-border">
  <tr>
    <td align="left" style="width: 33%;"><strong>Surveillant Général :</strong></td>
    <td style="width: 33%;"></td>
    <td style="width: 33%;"></td>
  </tr>
</table>

</body>
</html>