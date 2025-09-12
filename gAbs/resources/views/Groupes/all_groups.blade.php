@extends('layouts.nav')

@section('content')

<div class="col-xxl">
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Sélectionner un Groupe</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-name">Sélectionner le Groupe:</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="group" aria-label="Default select example">
                            <option selected>Sélectionner le Groupe:</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" data-id="{{$group->id}}" data-groupe="{{$group->groupe}}"data-code_groupe="{{ $group->code_groupe }}" data-num_groupe="{{$group->num_groupe}}" data-annee_formation="{{$group->annee_formation}}">
                                {{ $group->code }} - {{ $group->groupe }} ({{ $group->code_groupe }} - {{ $group->num_groupe }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
               

    <div class="card">
                <h5 class="card-header">Groupes Sélectionnés</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped d-none" id="groupTable">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Nom du Groupe</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                    </tbody>
                  </table>
                </div>
              </div>
             


              <!-- Modal -->
<div class="modal fade" id="stagiaireModal" tabindex="-1" aria-labelledby="stagiaireModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="stagiaireModalLabel">Liste des Stagiaires</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Num Inscription</th>
                <th>Nom et Prénom</th>
                <th>Tot. Absences</th>
              </tr>
            </thead>
            <tbody id="stagiaireTableBody">
              <!-- Rows will be appended here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            

            $('#group').change(function(){
                let selectedGroup = $("#group option:selected").data();
                if (selectedGroup.id) {
                    let row = `
                        <tr>
                        @if(Auth::user()->can('stagiaire.list'))
                            <td><button type="button" class="btn btn-info" onclick="fetchStagiaires(${selectedGroup.id})">Afficher Stagiaires</button></td>
                        @endif
                            <td>${selectedGroup.num_groupe} - ${selectedGroup.groupe}(${selectedGroup.code_groupe}) - Année : ${selectedGroup.annee_formation}</td>
                        
                            @if(Auth::user()->can('stagiaire.add'))
                            <td>
                                <form action="{{ route('stagiaires.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="group_id" value="${selectedGroup.id}" />
                                    <input type="file" name="file" accept=".xlsx,.csv" required />
                                    <button type="submit" class="btn-primary">Ajouter Stagiaires</button>
                                </form>
                            </td>
                            @endif
                        </tr>`;
                    $('#groupTable tbody').html(row);
                    $('#groupTable').removeClass("d-none")
                    $('#groupTable').show();
                }
            });
        });

      

        function fetchStagiaires(groupId) {
  $.ajax({
    url: `/stagiaires/by-groupe/${groupId}`,
    type: 'GET',
    success: function(data) {
      let tableBody = '';

      if (data.stagiaires.length === 0) {
        tableBody = '<tr><td colspan="3">Aucun stagiaire trouvé pour ce groupe.</td></tr>';
      } else {
        data.stagiaires.forEach(function(stagiaire) {
          let abs = stagiaire.total_absences;
let capped = Math.min(abs, 10);
let percent = (capped / 10) * 100;

// Interpolate color between yellow (255,255,0) and red (255,0,0)
let greenValue = 255 - Math.round((capped / 10) * 255);
let color = `rgb(255, ${greenValue}, 0)`; // from yellow to red

tableBody += `<tr>
  <td>${stagiaire.user_id_number}</td>
  <td>${stagiaire.name}</td>
  <td>
    <div class="progress mt-2" style="height: 20px;">
      <div class="progress-bar" 
           style="width: ${percent}%; background-color: ${color};">
        ${abs}
      </div>
    </div>
  </td>`;
        });
      }

      $('#stagiaireTableBody').html(tableBody);
      new bootstrap.Modal(document.getElementById('stagiaireModal')).show();
    },
    error: function() {
      alert('Erreur lors du chargement des stagiaires.');
    }
  });


}
    </script>
@endsection
