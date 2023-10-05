<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Document Upload</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/resources/assets/css/style.css')}}">
        <!-- Styles -->
        <style>
            .card-class{
                margin-bottom:20px;
            }
        </style>
    </head>
    <body>

<html>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <center><h1>Document scanning and find keywords</h1></center>

            <div class="card card-class">
                <div class="card-body">
                    <form action="{{route('uploadDocument')}}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        <div class="form-group {{ $errors->has('document') ? ' has-error' : '' }}">
                            <label for="document">Upload Document</label>
                            <input class="form-control" type="file" id="document" name="document"  accept=".doc, .docx, .pdf" required/>
                            @if ($errors->has('document'))
                                <span class="help-block alert alert-danger">
                                    <strong>{{ $errors->first('document') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit" id="product_submit" class="btn btn-primary pull-right">Upload</button>
                    </form>
                </div>
            </div>

            <div class="card card-class">
                <div class="card-body">
                    @isset($lastDocument)
                        <p>Last Added Document: <a href="{{(url('/public/storage/uploads/documents/') . '/') . $lastDocument->name}}" target="_blank" class="btn btn-success pull-right">View Document</a></p>
                    @endisset
                    <form action="{{route('searchWord')}}" method="post">
                        @csrf
                        <div class="form-group {{ $errors->has('document') ? ' has-error' : '' }}">
                            <label for="search">Search</label>
                            <input class="form-control" type="text" id="keyword" name="keyword"  placeholder="Enter keyword" required/>
                            @if ($errors->has('document'))
                                <span class="help-block alert alert-danger">
                                    <strong>{{ $errors->first('document') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit" id="product_submit" class="btn btn-primary pull-right">Search</button>
                    </form>

                    
                </div>
            </div>

            @if(isset($searchResults))
                @if(count($searchResults) > '0')
                    <div class="card card-class">
                        <div class="card-body">
                            <div id="results">
                                <h4>Search results : </h4>
                                @foreach($searchResults as $result)
                                    <p>{{ $result }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <p>No result found.</p>
                @endif
            @endif

        </div>
    
    </div>

    
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
