<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Weekly Rota</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        
        <!-- Styles -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="jumbotron text-center">
          <h1>Weekly Rota</h1>
          <p>Staff shift times</p>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                  <th></th>
                  @foreach ($table['days'] as $day => $data)
                    <th class="bg-primary">{{ jddayofweek($day, 1) }}</th>
                  @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($table['staff'] as $staffid=> $staffDays)
                <tr>
                  <td class="bg-danger">{{ $faker->name() }} ({{ $staffid }})</td>
                  @foreach ($staffDays as $day => $staffData)
                    <td>
                        @if ($staffData['slottype'] == 'dayoff')
                            OFF
                        @else
                            {{ date('H:i', strtotime($staffData['starttime'])) }} to {{ date('H:i', strtotime($staffData['endtime'])) }}
                        @endif
                    </td>
                  @endforeach
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>   
                  <td class="bg-info"><strong>Working Hours</strong></th>
                  @foreach ($table['days'] as $day => $data)
                    <td class="bg-info">{{ floor($data['working_hours']) }}:{{ str_pad(round(($data['working_hours'] - floor($data['working_hours'])) * 60), 2 , '0') }}</th>
                    @endforeach
                </tr>
                <tr>
                  <td class="bg-info"><strong>Premium Minutes</strong></th>
                  @foreach ($table['days'] as $day => $data)
                    <td class="bg-info">{{ $data['premium_minutes'] }}</th>
                  @endforeach
                </tr>
            </tfoot>
        </table>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
        </script>
    </body>
</html>
