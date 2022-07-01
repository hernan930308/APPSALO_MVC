<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Escribe tu nuevo password a continuación</p>

<?php include_once __DIR__ .  '/../templates/alertas.php'; ?>

<?php if($error) return; ?>
<form aclass="formulario" method="POST"> <!-- Se elimina el accion porque elimina el token -->
    <div class="campo">
        <label for="pasword">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu nuevo Password"       
        />
    </div>  
    <input type="submit" class="boton" value="Guardar Nuevo Password">  
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
    <a href="/crear">¿Aún no tienes una cuenta? Crea una</a>
</div>