<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Termékkereső</title>
</head>
<body>
  <h2>Termékkereső</h2>
  <form id="searchProductForm">
    Nem:
    <select id="nemValaszt" name="nem">
      <option value="fiu">Fiú</option>
      <option value="lany">Lány</option>
    </select>

    Kategória:
    <select id="kategoriaValaszt" name="kategoria">
      <option value="ruha">Ruha</option>
      <option value="cipo">Cipő</option>
    </select>

    Típus:
    <select id="tipusValaszt" name="tipus">
      <!-- Options will be added dynamically based on selected category -->
    </select>

    Méret:
    <select id="meretValaszt" name="meret">
      <!-- Options will be added dynamically based on selected gender and category -->
    </select>

    <input type="submit" value="Keresés">
  </form>

  <script>
    const tipusValaszt = document.getElementById('tipusValaszt');
    const meretValaszt = document.getElementById('meretValaszt');
    const nemValaszt = document.getElementById('nemValaszt');
    const kategoriaValaszt = document.getElementById('kategoriaValaszt');

    kategoriaValaszt.addEventListener('change', updateTypeAndSizeOptions);
    nemValaszt.addEventListener('change', updateTypeAndSizeOptions);

    function updateTypeAndSizeOptions() {
      const kategoria = kategoriaValaszt.value;
      const nem = nemValaszt.value;
      tipusValaszt.innerHTML = ''; // Clear previous options
      meretValaszt.innerHTML = ''; // Clear previous options

      if (kategoria === 'ruha') {
        if (nem === 'fiu') {
          tipusValaszt.innerHTML = `
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
          `;
          meretValaszt.innerHTML = `
            <option value="1">XS</option>
            <option value="2">S</option>
            <option value="3">M</option>
            <option value="4">L</option>
            <option value="5">XL</option>
            <option value="6">XXL</option>
          `;
        } else if (nem === 'lany') {
          tipusValaszt.innerHTML = `
            <option value="3">Felső</option>
            <option value="4">Alsó</option>
            <option value="5">Egybe ruha</option>
            <option value="6">Kiegészítő</option>
            <option value="7">Fehérnemű</option>
          `;
          meretValaszt.innerHTML = `
            <option value="1">XS</option>
            <option value="2">S</option>
            <option value="3">M</option>
            <option value="4">L</option>
            <option value="5">XL</option>
            <option value="6">XXL</option>
          `;
        }
      } else if (kategoria === 'cipo') {
        if (nem === 'fiu') {
          tipusValaszt.innerHTML = `
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
          `;
          meretValaszt.innerHTML = `
            <option value="11">39</option>
            <option value="12">40</option>
            <option value="13">41</option>
            <option value="14">42</option>
            <option value="15">43</option>
            <option value="16">44</option>
            <option value="17">45</option>
            <option value="18">46</option>
            <option value="19">47</option>
          `;
        } else if (nem === 'lany') {
          tipusValaszt.innerHTML = `
            <option value="8">Szabadidőcipő</option>
            <option value="9">Sportcipő</option>
            <option value="10">Bakancs</option>
            <option value="11">Papucs</option>
            <option value="12">Szandál</option>
            <option value="13">Körömcipő</option>
          `;
          meretValaszt.innerHTML = `
            <option value="7">35</option>
            <option value="8">36</option>
            <option value="9">37</option>
            <option value="10">38</option>
            <option value="11">39</option>
            <option value="12">40</option>
            <option value="13">41</option>
            <option value="14">42</option>
          `;
        }
      }
    }

    // Initial update
    updateTypeAndSizeOptions();
  </script>
</body>
</html>