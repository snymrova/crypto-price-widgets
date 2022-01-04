function wx_crypto_shortcodes_render_chart(chart_container_id) {
  var chart_container = "#" + chart_container_id;
  var ticker_container = chart_container + " .wx-crypto-shortcodes-ticker";
  var exchange_link = ticker_container + " .exchange-link";
  var market_code = ticker_container + " .market-code";
  var price_heading = ticker_container + "  .market-price";
  var price_details = ticker_container + "  .details";
  var price_details_low = price_details + " .low";
  var price_details_high = price_details + " .high";
  var referral_code = jQuery(chart_container).data('referral_code');
  var toolbar_container = chart_container + " .toolbar";
  var til = toolbar_container + " .time-interval-loading";

  var formatter = new Intl.NumberFormat("en-IN", {
    style: "currency",
    currency: "INR",
    maximumSignificantDigits: 3,
  });

  var options = {
    chart: {
      type: "area",
      width: "100%",
    },
    series: [],
    title: {
      text: "Loading...",
    },
    noData: {
      text: "Loading...",
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return formatter.format(value);
        },
      },
    },
    xaxis: {
      type: "datetime",
      labels: {
        datetimeUTC: false,
      },
    },
    dataLabels: {
      enabled: false,
    },
  };

  var chart = new ApexCharts(
    document.querySelector(chart_container + " .chart"),
    options
  );
  chart.render();

  var request_chart_data = function (data, callback) {
    if (!data) {
      data = {};
    }
    data.market = jQuery(chart_container).data("market");

    jQuery.getJSON(wx_crypto_shortcode_charts_api.url, data, callback);
  };

  var render_chart_data = function (response) {
    jQuery(price_heading).text(formatter.format(response.ticker.lastPrice));
    jQuery(price_details_low).text(formatter.format(response.ticker.lowPrice));
    jQuery(price_details_high).text(
      formatter.format(response.ticker.highPrice)
    );
    jQuery(market_code).text(response.ticker.name);
    jQuery(exchange_link).attr(
      "href",
      "https://wazirx.com/exchange/" +
        response.ticker.name +
        referral_code
    );
    jQuery(ticker_container).show();
    chart.updateOptions({
      title: {
        text: response.title,
      },
    });
    chart.updateSeries([
      {
        name: response.market,
        data: response.entries,
      },
    ]);
    time_interval_loading_done();
  };
  var time_series_action = function () {
    jQuery(toolbar_container + " a").click(function (e) {
      e.preventDefault();
      time_interval_loading();
      jQuery(toolbar_container + " a").removeClass("active");
      $this = jQuery(this);
      $this.addClass("active");
      data = { "time-interval": $this.data("time-interval") };
      request_chart_data(data, render_chart_data);
    });
  };
  var time_interval_loading = function () {
    jQuery(til).show();
  };
  var time_interval_loading_done = function () {
    jQuery(til).hide();
  };
  request_chart_data({ "time-interval": jQuery(toolbar_container + " a.active").data("time-interval")  }, render_chart_data);
  time_series_action();
}
