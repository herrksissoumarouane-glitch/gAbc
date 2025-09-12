@extends('layouts.nav')
@section('title', 'Permissions')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Add Permission /</span> Add Permission</h4>


  <!-- Basic Layout -->
  <div class="col-xxl">
                  <div class="card mb-4">
                    <div class="card-body">
                    <form id="myForm" method="post" action="{{ route('store.permission') }}"  >
                        @csrf
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="basic-default-name">Permission Name</label>
                          <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" id="basic-default-name" placeholder="Permission Name" />
                          </div>
                        </div>
                        <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-email">Group Name</label>
                        <div class="col-sm-10">
                            <select name="group_name" class="form-select" aria-label="Default select example">
                                <option selected="">Open this select Group</option>
                                <option value="formateur">Formateur</option>
                                <option value="groupe">Groupe</option>
                                <option value="stagiaires">Stagiaires</option>
                                <option value="absences">Absences</option>
                                <option value="filiere">Filiere</option>
                                <option value="complexe">Complexe</option>
                                <option value="efp">Efp</option>
                                <option value="setting">Setting</option>
                                <option value="role">Role</option>
                                <option value="admin">Admin</option>
                            </select>
                          </div>
                        </div>
                       
                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                            <input type="submit" class="btn btn-primary" value="Enregistrer" />
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- Basic with Icons -->

<hr class="my-5" />
@endsection 