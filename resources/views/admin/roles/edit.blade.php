 <!DOCTYPE html>
<html>
<style>

body{
  background-color: #faebd7;
  text-align: center;
}
.button {
  width: 50%;
  padding: 1em 2em;
  border: none;
  border-radius: 15px;
  font-weight: bold;
  letter-spacing: 5px;
  text-transform: uppercase;
  color: #2c9caf;
  transition: all 1000ms;
  font-size: 15px;
  position: relative;
  overflow: hidden;
  outline: 2px solid #2c9caf;
}

button:hover {
  color: #ffffff;
  transform: scale(1.1);
  outline: 2px solid #70bdca;
  box-shadow: 4px 5px 17px -4px #268391;
}


.button1 {
  width: 50%;
  padding: 1em 10em;
  border: none;
  border-radius: 15px;
  font-weight: bold;
  letter-spacing: 5px;
  text-transform: uppercase;
  color: #2c9caf;
  transition: all 1000ms;
  font-size: 15px;
  position: relative;
  overflow: hidden;
  outline: 2px solid #2c9caf;
}
.button1:hover {
  color: #ffffff;
  transform: scale(1.1);
  outline: 2px solid #32f900;
  box-shadow: 4px 5px 17px -4px #32f900;
}

.button1::before {
  content: "";
  position: absolute;
  left: -50px;
  top: 0;
  width: 0;
  height: 100%;
  background-color: #32f900;
  transform: skewX(45deg);
  z-index: -1;
  transition: width 1000ms;
}

button::before {
  content: "";
  display: flex ;
  position: absolute;
  left: -50px;
  top: 0;
  width: 0;
  height: 100%;
  background-color: #2c9caf;
  transform: skewX(45deg);
  z-index: -1;
  transition: width 1000ms;
}

button:hover::before {
  width: 250%;
}


.button2 {
  width: 50%;
  padding: 1em 2em;
  border: none;
  border-radius: 15px;
  font-weight: bold;
  letter-spacing: 5px;
  text-transform: uppercase;
  color: #2c9caf;
  transition: all 1000ms;
  font-size: 15px;
  position: relative;
  overflow: hidden;
  outline: 2px solid #2c9caf;
}
.button2:hover {
  color: #ffffff;
  transform: scale(1.1);
  outline: 2px solid #f90000;
  box-shadow: 4px 5px 17px -4px #f90000;
}

.button2::before {
  content: "";
  position: absolute;
  left: -50px;
  top: 0;
  width: 0;
  height: 100%;
  background-color: #f90000;
  transform: skewX(45deg);
  z-index: -1;
  transition: width 1000ms;
}


div {
  border-radius: 15px;
  background-color: #726e6e;
  padding: 50px;
  width: 90%;
  
}

.h3{
  text-align: center;
}
h3{
  text-align: center;
  color: #fff
}


select {
  display: inline;
  font-size: 16px;
  font-weight: bold;
  padding: 20px;
  margin: 5px ;
  width: 100%;
  background-color: #f2f2f2;
  border: none;
  border-radius: 15px;
  color: #30ef00;
}

option {
  text-align: center;
  border-radius: 15px;
  font-size: 14px;
  font-weight: bolder;
  padding: 5px;
  margin: 5px;
  background-color: #fff;
  color: #333;
}

select:focus {
  outline: none;
  box-shadow: 0 0 5px #9eedb3;
}

</style>
<body> 

<h2 class="h3"> {{$user->name}}'s ROLE is ({{$user->roles[0]->name}})</h2>
<form action="{{ route('user.change.role', $user->id) }}" method="POST">
  @csrf
  @method('PUT')
  <div class="form-group">
     <label for="roles"> <h3>Select Role:</h3></label>
     <select name="roles[]" id="roles" class="form-control" multiple>
        @foreach($roles as $role)
           <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
     </select>
  </div>
  <hr>
  <button type="submit" class="button">Assign</button>
  <hr>
</form>
<a href="{{route ('admin.roles.index')}}"><button type="submit" class="button1">Back</button></a>
<hr>
<a href="{{route ('admin.roles.index')}}"><button type="submit" class="button2">Cancel</button></a>

  
</body>
