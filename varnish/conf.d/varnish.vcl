backend default {
    .host = "webserver";
    .port = "8080";
}

sub vcl_recv {
    # Set the X-Forwarded-For header so the backend can see the original
    # IP address. If one is already set by an upstream proxy, we'll just re-use that.
    # if (client.ip ~ upstream_proxy && req.http.X-Forwarded-For) {
    #   set req.http.X-Forwarded-For = req.http.X-Forwarded-For;
    # } else {
    #   set req.http.X-Forwarded-For = regsub(client.ip, ":.*", "");
    # }
    ## Remove has_js and Google Analytics cookies.
    set req.http.Cookie = regsuball(req.http.Cookie, "(^|;\s*)(__[a-z]+|has_js)=[^;]*", "");
    ## Remove a .;. prefix, if present.
    set req.http.Cookie = regsub(req.http.Cookie, "^;\s*", "");
    ## Remove empty cookies.
    if (req.http.Cookie ~ "^\s*$") {
       unset req.http.Cookie;
    }
    # Cache static files
    if (req.url ~ "^/storage/.*") {
       unset req.http.Cookie;
    }

    # js/css doesn't need cookies, cache them
    if (req.url ~ "^/(js|css)(.*)?") {
       unset req.http.Cookie;
    }
}

sub vcl_hash {
   if (req.http.Cookie) {
      hash_data(req.http.Cookie);
   }
}
