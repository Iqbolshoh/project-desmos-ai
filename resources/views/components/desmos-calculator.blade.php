<div id="calculator-{{ $id ?? 'main' }}" style="width: 100%; height: {{ $height ?? '400px' }};" class="rounded-xl overflow-hidden border border-gray-700"></div>

<script src="https://www.desmos.com/api/v1.9/calculator.js?apiKey=dcb31709b452b1cf9dc26972add0fda6"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elt = document.getElementById('calculator-{{ $id ?? "main" }}');
        var calculator = Desmos.GraphingCalculator(elt, {
            keypad: {{ $keypad ?? 'false' }},
            expressions: {{ $expressions ?? 'false' }},
            settingsMenu: false,
            zoomButtons: true,
            lockViewport: false
        });
        
        @if(isset($expression))
            calculator.setExpression({ id: 'graph1', latex: '{!! addslashes($expression) !!}' });
        @endif
        
        @if(isset($state))
            calculator.setState({!! $state !!});
        @endif
        
        // Expose calculator to global scope if an ID is provided to easily get state
        @if(isset($id))
            window['desmos_{{ $id }}'] = calculator;
        @endif
    });
</script>
