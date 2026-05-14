defmodule PhoenixApiWeb.Plugs.RateLimit do
  import Plug.Conn
  import Phoenix.Controller

  alias PhoenixApi.RateLimiter

  @user_limit 5
  @user_window_ms :timer.minutes(10)

  @global_limit 1000
  @global_window_ms :timer.hours(1)

  def init(opts), do: opts

  def call(conn, _opts) do
    current_user = conn.assigns.current_user

    with :ok <- check_user_limit(current_user.id),
         :ok <- check_global_limit() do
      conn
    else
      {:error, retry_after} ->
        conn
        |> put_resp_header("retry-after", Integer.to_string(retry_after))
        |> put_status(:too_many_requests)
        |> json(%{
          error: "Rate limit exceeded",
          retry_after_seconds: retry_after
        })
        |> halt()
    end
  end

  defp check_user_limit(user_id) do
    key = "photo-index-user:#{user_id}"

    case RateLimiter.hit(key, @user_window_ms, @user_limit) do
      {:allow, _count} ->
        :ok

      {:deny, retry_after} ->
        {:error, retry_after}
    end
  end

  defp check_global_limit do
    key = "photo-index-global"

    case RateLimiter.hit(key, @global_window_ms, @global_limit) do
      {:allow, _count} ->
        :ok

      {:deny, retry_after} ->
        {:error, retry_after}
    end
  end
end
