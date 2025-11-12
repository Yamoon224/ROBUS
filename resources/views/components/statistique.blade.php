   <div class="grid grid-cols-12 gap-6 mt-6">

       <!-- BEGIN: Sales Report -->
       <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
           <div class="intro-y block sm:flex items-center h-10">
               <h2 class="text-lg font-medium truncate mr-5">
                   Gains en {{ $usersCountry }}
               </h2>
           </div>
           <div class="intro-y box p-5 mt-12 sm:mt-5">
               <canvas id="sum" sum="{{ json_encode($reloads_gain) }}"></canvas>
           </div>
       </div>
       <!-- END: Sales Report -->
       <!-- BEGIN: Sales Report -->
       <!-- BEGIN: Sales Report -->
       <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
           <div class="intro-y flex items-center h-10">
               <h2 class="text-lg font-medium truncate mr-5">
                   Les statistiques de l'année en {{ $usersCountry }}
               </h2>
           </div>
           <div class="intro-y box p-5 mt-12 sm:mt-5">
               <canvas id="reload" reload="{{ json_encode($realods) }}"></canvas>
           </div>
       </div>
       <!-- END: Sales Report -->
       <!-- END: Sales Report -->
   </div>

   @stack('scripts')
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script>
       var relaods = JSON.parse($('#reload').attr('reload'));
       var sumReload = JSON.parse($('#sum').attr('sum'));

       var _ctx = $('#reload');
       var _ctx2 = $('#sum');

       new Chart(_ctx, {
           type: 'bar',
           data: {
               labels: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                   'Octobre', 'Novembre', 'Décembre'
               ],
               datasets: [{
                   label: 'Rechargement',
                   data: relaods,
                   backgroundColor: [
                       'rgba(255, 99, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                       'rgba(153, 102, 255,1)',
                       'rgba(201, 203, 207,1)',
                       'rgba(255, 0, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                   ],
                   borderColor: [
                       'rgba(255, 99, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                       'rgba(153, 102, 255,1)',
                       'rgba(201, 203, 207,1)',
                       'rgba(255, 0, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                   ],
                   borderWidth: 1,
               }]
           },
           options: {
               responsive: true,
               maintainAspectRatio: true,
               plugins: {
                   labels: {
                       render: 'percentage',
                       fontColor: ['white', 'white'],
                   }
               },
           }
       });

       new Chart(_ctx2, {
           type: 'bar',
           data: {
               labels: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                   'Octobre', 'Novembre', 'Décembre'
               ],
               datasets: [{
                   label: 'Rechargement',
                   data: sumReload,
                   backgroundColor: [
                       'rgba(255, 99, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                       'rgba(153, 102, 255,1)',
                       'rgba(201, 203, 207,1)',
                       'rgba(255, 0, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                   ],
                   borderColor: [
                       'rgba(255, 99, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                       'rgba(153, 102, 255,1)',
                       'rgba(201, 203, 207,1)',
                       'rgba(255, 0, 132,1)',
                       'rgba(255, 159, 64,1)',
                       'rgba(255, 205, 86,1)',
                       'rgba(75, 192, 192,1)',
                       'rgba(54, 162, 235,1)',
                   ],
                   borderWidth: 1,
               }]
           },
           options: {
               responsive: true,
               maintainAspectRatio: true,
               plugins: {
                   labels: {
                       render: 'percentage',
                       fontColor: ['white', 'white'],
                   }
               },
           }
       });
   </script>
