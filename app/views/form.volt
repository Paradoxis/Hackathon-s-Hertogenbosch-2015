<h1>VacationChallange playground page</h1>
<p>The reason for this form is to test request / responses on pages.</p>
<p>This page should NOT be exposed to the public when released on live servers.</p>

{# AuthController #}
<br/><br/>
<h2>Authorisation</h2>

{# Register user #}
<form action="/api/auth/register" method="post">
    <fieldset>
        <legend>Register</legend>
        Email: <input type="text" name="email" /><br/>
        Password: <input type="password" name="password" /><br/>
        Phone: <input type="text" name="phone" /><br/>
        Firstname: <input type="text" name="firstname" /><br/>
        Lastname: <input type="text" name="lastname" /><br/>
        Prefix: <input type="text" name="prefix" /><br/>
        Hash password: <input type="checkbox" name="hash-password" value="1" checked="checked" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Login #}
<form action="/api/auth/login" method="post">
    <fieldset>
        <legend>Login</legend>
        Email: <input type="text" name="email" placeholder="email" /><br/>
        Password: <input type="password" name="password" placeholder="password" /><br/>
        Hash password: <input type="checkbox" name="hash-password" value="1" checked="checked" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Logout #}
<form action="/api/auth/logout" method="post">
    <fieldset>
        <legend>Logout</legend>
        Token: <input type="text" name="token" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>



{# UsersController #}
<br/><br/>
<h2>Users</h2>

{# get your own profile #}
<form action="/api/users/getProfile" method="post">
    <fieldset>
        <legend>Get your profile</legend>
        Token: <input type="text" name="token" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Get someone elses profile #}
<form action="/api/users/getProfile" method="post">
    <fieldset>
        <legend>Get another profile</legend>
        User id: <input type="number" step="1" name="id" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>



{# TripsController #}
<br/><br/>
<h2>Trips</h2>

{# Add booking #}
<form action="/api/trip/addBooking" method="post">
    <fieldset>
        <legend>Add booking</legend>
        Token: <input type="text"  name="token" /><br/>
        TripId: <input type="number" step="1" name="tripId" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Get bookings by your user account #}
<form action="/api/trip/getBookings" method="post">
    <fieldset>
        <legend>Get bookings</legend>
        Token: <input type="text" name="token" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Get all trips #}
{# No authorisation or specification required #}
<form action="/api/trip/getTrips" method="post">
    <fieldset>
        <legend>Get all trips</legend>
        <button type="submit">Submit</button>
    </fieldset>
</form>



{# ChallengesController #}
<br/><br/>
<h2>Challenges</h2>

{# Get all the possible challenge types #}
<form action="/api/challenges/getChallengeTypes" method="post">
    <fieldset>
        <legend>Get all available challenge types</legend>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Add a challenge #}
<form action="/api/challenges/addChallenge" method="post">
    <fieldset>
        <legend>Add a challenge</legend>
        Token: <input type="text" name="token" /><br/>
        Challenge name: <input type="text" name="name" /><br/>
        Challenge description: <input type="text" name="description" /><br/>
        Type id: <input type="number" name="typeId" /><br/>
        Trip id (optional): <input type="number" name="tripId" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Get all challenges #}
<form action="/api/challenges/getChallenges" method="post">
    <fieldset>
        <legend>Get all challenges</legend>
        Start (optional): <input type="number" name="start" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Get all user challenges #}
<form action="/api/challenges/getUserChallenges" method="post">
    <fieldset>
        <legend>Get all user challenges</legend>
        Token: <input type="text" name="token" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Start a user related challene #}
<form action="/api/challenges/startChallenge" method="post">
    <fieldset>
        <legend>Start a user challenge</legend>
        Token: <input type="text" name="token" /><br/>
        Challenge id: <input type="number" name="id" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>

{# Complete a user related challene #}
<form action="/api/challenges/completeChallenge" method="post">
    <fieldset>
        <legend>Complete a user challenge</legend>
        Token: <input type="text" name="token" /><br/>
        Challenge id: <input type="number" name="id" /><br/>
        <button type="submit">Submit</button>
    </fieldset>
</form>