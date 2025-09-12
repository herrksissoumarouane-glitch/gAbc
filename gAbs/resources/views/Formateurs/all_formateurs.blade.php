 @extends('layouts.nav')

@section('content')
 
<div class="page-content">
    @if(Auth::user()->can('admin.add'))
    <form action="{{ route('formateurs.groupes.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Select File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="type" class="form-label">Select Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="vacataire">Vacataire</option>
                <option value="permanent">Permanent</option>
                <option value="gestionnaire">Gestionnaire de Stagiaires</option>
                <option value="directeur">Directeur</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
    @endif
        
        <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            let fileName = event.target.files[0] ? event.target.files[0].name : "Importer";
            document.getElementById('importButton').textContent = "Importer: " + fileName;
        });
        </script>
    @if(Auth::user()->can('admin.list'))
    <div class="container mt-5">

        <h2>Formateurs</h2>
         
        <button id="vacataireBtn" class="btn btn-primary mb-3" onclick="showTable('vacataire')">Afficher Vacataires</button>
        <button id="permanentBtn" class="btn btn-primary mb-3" onclick="showTable('permanent')">Afficher Permanents</button>
        <button id="gestionnaireBtn" class="btn btn-primary mb-3" onclick="showTable('gestionnaire')">Afficher gestionnaires</button>

        <button id="directeurBtn" class="btn btn-primary mb-3" onclick="showTable('directeur')">Afficher directeurs</button>

        <div id="vacataireTable" style="display: none;">
            <h3>Vacataires</h3>
            <table class="table table-bordered">
                <thead>
                    <tr> 
                        <th>Nom et Prénom</th>
                        <th>Email Edu</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacataires as $vacataire)
                        <tr> 
                            <td>{{ $vacataire->name }}</td>
                            <td>{{ $vacataire->email }}</td>
                            <td>{{ $vacataire->type }}</td>
                            <td><a href="{{ route('formateurs.affecter.groupes',$vacataire->id) }}" class="btn btn-info">Affecter groupes</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
 
        <div id="permanentTable" style="display: none;">
            <h3>Permanents</h3>
            <table class="table table-bordered">
                <thead>
                    <tr> 
                        <th>Nom et Prénom</th>
                        <th>Email Edu</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permanents as $permanent)
                        <tr> 
                            <td>{{ $permanent->name }}</td>
                            <td>{{ $permanent->email }}</td>
                            <td>{{ $permanent->type }}</td>
                            <td><a href="{{ route('formateurs.affecter.groupes',$permanent->id) }}" class="btn btn-info">Affecter groupes</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="gestionnaireTable" style="display: none;">
            <h3>Vacataires</h3>
            <table class="table table-bordered">
                <thead>
                    <tr> 
                        <th>Nom et Prénom</th>
                        <th>Email Edu</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gStagiaires as $gStagiaire)
                        <tr> 
                            <td>{{ $gStagiaire->name }}</td>
                            <td>{{ $gStagiaire->email }}</td>
                            <td>{{ $gStagiaire->type }}</td>
                            <td><a href="{{ route('formateurs.affecter.groupes',$gStagiaire->id) }}" class="btn btn-info">Affecter groupes</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div id="directeurTable" style="display: none;">
            <h3>Directeurs</h3>
            <table class="table table-bordered">
                <thead>
                    <tr> 
                        <th>Nom et Prénom</th>
                        <th>Email Edu</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($directeurs as $directeur)
                        <tr> 
                            <td>{{ $directeur->name }}</td>
                            <td>{{ $directeur->email }}</td>
                            <td>{{ $directeur->type }}</td>
                            <td><a href="{{ route('formateurs.affecter.groupes',$directeur->id) }}" class="btn btn-info">Affecter groupes</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    </div>
    <script> 
        function showTable(type) {
            document.getElementById('vacataireTable').style.display = 'none';
            document.getElementById('permanentTable').style.display = 'none';
            document.getElementById('gestionnaireTable').style.display = 'none';
            document.getElementById('directeurTable').style.display = 'none';

            // Show the selected table
            if (type === 'vacataire') {
                document.getElementById('vacataireTable').style.display = 'block';
            } else if (type === 'permanent') {
                document.getElementById('permanentTable').style.display = 'block';
            } else if (type === 'gestionnaire') {
                document.getElementById('gestionnaireTable').style.display = 'block';
            }else if (type === 'directeur') {
                document.getElementById('directeurTable').style.display = 'block';
            }
        }
 
        showTable('vacataire');
    </script>
 
 
@endsection
