<style>
Form#представление{
  display: flex; justify-content: flex-end
}

Form#представление > *:not(:last-child){ margin-right: 0.2rem }
</style>


<form id='представление' method='post' action='/_php/i-n.php'>
 <input type='text' placeholder='Login' name='l'>
 <input type='password' placeholder='Password' name='p'>
 <button>Enter</button>
</form>