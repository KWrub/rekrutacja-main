defmodule PhoenixApi.RateLimiter do
  use Hammer, backend: :ets
end
