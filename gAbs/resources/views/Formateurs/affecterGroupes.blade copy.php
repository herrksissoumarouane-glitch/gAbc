@extends('layouts.nav')

@section('content')

    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Sélectionner un Groupe</h5>
            </div>
            <input
                            type="hidden"
                            class="form-control"
                            value={{$idFormateur}}
                            id="idformateur"/>
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="idComplexe">Sélectionner un Complexe:</label>
                    <div class="col-sm-10">
                        <input
                            type="text"
                            class="form-control"
                            id="idComplexe"
                            placeholder="Tapez un complexe à rechercher"/>
                        <div id="complexeList" class="list-group position-absolute bg-white"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="efp">Sélectionner l'EFP:</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="efp" aria-label="Default select example">
                            <option selected>Sélectionner l'EFP</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="filiere">Sélectionner la Filière:</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="filiere" aria-label="Default select example">
                            <option selected>Sélectionner la Filière</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3" id="groupesCheckboxes">
                    <label class="col-sm-2 col-form-label" for="groupes">Sélectionner un Groupe:</label>
                    <div class="col-sm-10">
                    <form method="POST" action="{{ route('Affect.groups') }}" id="groupForm">
                        @csrf
                        <input type="hidden" name="idFormateur" id="idformateur" value="{{ $idFormateur }}"> <!-- pass the user_id -->
                        <input type="hidden" name="filiere_id" id="filiere_id" value="">
                        <div id="groupesList">
                            <!-- Checkboxes will be dynamically inserted here -->
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Groups</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        // Autocomplete
        $('#idComplexe').keyup(function () {
            let query = $(this).val();
            if (query !== '') {
                $.ajax({
                    url: '/get-complexes/',
                    method: 'GET',
                    data: { query: query },
                    success: function (data) {
                        $('#complexeList').fadeIn().html('');
                        if (data.length > 0) {
                            data.forEach(item => {
                                $('#complexeList').append('<a href="#" class="list-group-item list-group-item-action" data-id="' + item.id + '">' + item.complexe + '</a>');
                            });
                        } else {
                            $('#complexeList').html('<div class="list-group-item">Aucun complexe trouvé</div>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("Error fetching data: ", error);
                    }
                });
            } else {
                $('#complexeList').fadeOut();
            }
        });

        // When selecting a complexe
        $(document).on('click', '#complexeList a', function (e) {
            e.preventDefault();
            let complexeName = $(this).text();
            let complexeId = $(this).data('id');
            $('#idComplexe').val(complexeName);
            $('#complexeList').fadeOut();

            // Get EFPs for selected complexe
            $.ajax({
                url: '/get-efps/' + complexeId,
                method: 'GET',
                success: function (data) {
                    $('#efp').html('<option selected>Sélectionner l\'EFP</option>');
                    data.forEach(item => {
                        $('#efp').append('<option value="' + item.id + '">' + item.efp + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.log("Error fetching EFPs: ", error);
                }
            });
        });

        // When selecting an EFP
        $('#efp').change(function () {
            let efpId = $(this).val();
            if (efpId) {
                // Get filieres for selected EFP
                $.ajax({
                    url: '/get-filieres/' + efpId,
                    method: 'GET',
                    success: function (data) {
                        $('#filiere').html('<option selected>Sélectionner la Filière</option>');
                        data.forEach(item => {
                            $('#filiere').append('<option value="' + item.id + '">' + item.filiere + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.log("Error fetching filieres: ", error);
                    }
                });
            }
        });

// When selecting a filière
$('#filiere').change(function () {
    let filiereId = $(this).val();
    let idFormateur = $('#idformateur').val(); // Assuming you have an input field with the id 'idformateur'
    if (filiereId) {
        // Get groupes for selected filière
        $.ajax({
            url: '/get-groupes/' + filiereId + '/' + idFormateur,
            method: 'GET',
            success: function (data) {
                $('#groupesList').html('');
                $('#filiere_id').val(filiereId);
                data.groupes.forEach(item => {
                    // Check if the groupe is assigned to the formateur
                    let isChecked = data.assignedGroupIds.includes(item.id) ? 'checked' : '';
                    $('#groupesList').append(
                        '<div class="form-check">' +
                            '<input class="form-check-input" type="checkbox" name="groupes[]" value="' + item.id + '" id="group_' + item.id + '" ' + isChecked + '>' +
                            '<label class="form-check-label" for="group_' + item.id + '">' + item.num_groupe +' - '+ item.groupe + ' - Année : '+ item.annee_formation +'</label>' +
                        '</div>'
                    );
                });
            },
            error: function (xhr, status, error) {
                console.log("Error fetching groupes: ", error);
            }
        });
    }
});


    });
</script>

@endsection
